<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    /**
     * Get payment configuration for frontend
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'public_key' => $this->paymentService->getPublicKey(),
            'is_test_mode' => $this->paymentService->isTestMode(),
            'supported_methods' => $this->paymentService->getSupportedMethods(),
            'currency' => 'UGX',
        ]);
    }
    
    /**
     * Initialize a payment. All real-money payments are routed through Marz:
     *   - payment_method=mtn|airtel  → Marz Mobile Money (USSD push)
     *   - payment_method=card|marz   → Marz hosted card checkout
     * When Marz is not configured the simulation path is used so dev keeps working.
     */
    public function initialize(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'name' => 'nullable|string',
            'type' => 'required|in:order,subscription,group_share,reservation,scheduled_order,wallet_deposit',
            'reference_id' => 'nullable|integer',
            'plan_id' => 'nullable|numeric',
            'payment_method' => 'required|in:mtn,airtel,card,marz',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $customerId = $this->getCustomerId();

        Log::info('Payment initialization', [
            'customer_id' => $customerId,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'method' => $data['payment_method'],
            'reference_id' => $data['reference_id'] ?? null,
        ]);

        // mtn / airtel → Marz mobile_money. card / marz → Marz card hosted checkout.
        $isMobileMoney = in_array($data['payment_method'], ['mtn', 'airtel'], true);
        $apiMethod = $isMobileMoney ? 'mobile_money' : 'card';

        if ($isMobileMoney && empty($data['phone'])) {
            return response()->json([
                'success' => false,
                'message' => 'A Mobile Money phone number is required for ' . strtoupper($data['payment_method']) . ' payments.',
            ], 422);
        }

        // Marz collection cap: mobile money is limited to UGX 200,000 per
        // transaction. Refuse early with an actionable message instead of
        // letting Marz reject the call with a generic error.
        $marzMomoLimit = 200000;
        if ($isMobileMoney && (float) $data['amount'] > $marzMomoLimit) {
            return response()->json([
                'success' => false,
                'code' => 'momo_limit_exceeded',
                'limit' => $marzMomoLimit,
                'message' => 'Mobile Money payments are capped at UGX '
                    . number_format($marzMomoLimit)
                    . ' per transaction. Please pay with a Debit/Credit card or split this payment.',
            ], 422);
        }

        // Simulation fallback only when Marz collection credentials are
        // genuinely absent (e.g. local dev). When Marz is configured we
        // ALWAYS charge for real — regardless of the legacy Flutterwave
        // isTestMode() heuristic.
        if (!$this->paymentService->hasMarzCredentials()) {
            try {
                $result = $this->paymentService->simulatePayment($data);
                $payment = Payment::create([
                    'customer_id' => $customerId,
                    'tx_ref' => $result['tx_ref'],
                    'amount' => $data['amount'],
                    'currency' => 'UGX',
                    'payment_method' => $data['payment_method'],
                    'payment_type' => $data['type'],
                    'reference_id' => $data['reference_id'] ?? null,
                    'status' => 'successful',
                    'provider' => 'demo',
                    'metadata' => [
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? null,
                        'name' => $data['name'] ?? null,
                        'plan_id' => $request->input('plan_id'),
                        'simulated' => true,
                    ],
                ]);

                if ($data['type'] === Payment::TYPE_SUBSCRIPTION && $customerId) {
                    $this->createSubscriptionFromPayment($payment, $request);
                }
                if ($data['type'] === Payment::TYPE_GROUP_SHARE) {
                    $this->markGroupSharePaid($payment);
                }
                if ($data['type'] === Payment::TYPE_SCHEDULED_ORDER) {
                    $this->markScheduledOrderPaid($payment);
                }
                if ($data['type'] === Payment::TYPE_WALLET_DEPOSIT) {
                    $this->markWalletDepositPaid($payment);
                }
                if ($data['type'] === Payment::TYPE_RESERVATION && $payment->reference_id) {
                    \Illuminate\Support\Facades\DB::table('reservations')
                        ->where('reservation_id', $payment->reference_id)
                        ->update([
                            'fee_paid' => 1,
                            'fee_transaction_id' => $payment->tx_ref,
                            'fee_paid_at' => now(),
                            'updated_at' => now(),
                        ]);
                }

                return response()->json([
                    'success' => true,
                    'tx_ref' => $result['tx_ref'],
                    'status' => 'successful',
                    'message' => 'Payment processed successfully (Demo Mode)',
                    'payment_id' => $payment->id,
                    'redirect_url' => null,
                ]);
            } catch (\Exception $e) {
                Log::error('Payment simulation error', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment processing error: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Real Marz initialization
        $result = $this->paymentService->initializeMarzPayment([
            'amount' => $data['amount'],
            'currency' => 'UGX',
            'country' => 'UG',
            'method' => $apiMethod,
            'email' => $data['email'],
            'phone' => $isMobileMoney ? $data['phone'] : null,
            'name' => $data['name'] ?? 'Customer',
            'description' => $this->paymentDescription($data['type']),
            'redirect_url' => route('payment.callback'),
            'meta' => [
                'type' => $data['type'],
                'reference_id' => $data['reference_id'] ?? null,
                'customer_id' => $customerId,
                'plan_id' => $request->input('plan_id'),
            ],
        ]);

        if (empty($result['success'])) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Payment initialization failed',
            ], 400);
        }

        $payment = Payment::create([
            'customer_id' => $customerId,
            'tx_ref' => $result['tx_ref'],
            'amount' => $data['amount'],
            'currency' => 'UGX',
            'payment_method' => $data['payment_method'],
            'payment_type' => $data['type'],
            'reference_id' => $data['reference_id'] ?? null,
            'status' => Payment::STATUS_PENDING,
            'provider' => 'marz',
            'metadata' => [
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'name' => $data['name'] ?? null,
                'plan_id' => $request->input('plan_id'),
                'api_method' => $apiMethod,
                'transaction_uuid' => $result['transaction_uuid'] ?? null,
            ],
        ]);

        // Stamp the group-order participant with the tx_ref so polling can find it.
        if ($data['type'] === Payment::TYPE_GROUP_SHARE && !empty($data['reference_id'])) {
            \App\Models\GroupOrderParticipant::where('id', $data['reference_id'])
                ->update(['payment_tx_ref' => $result['tx_ref']]);
        }

        return response()->json([
            'success' => true,
            'tx_ref' => $result['tx_ref'],
            'payment_id' => $payment->id,
            'redirect_url' => $result['link'] ?? null,
            'pending' => empty($result['link']),
            'message' => !empty($result['link'])
                ? 'Redirecting to Marz checkout...'
                : 'A Mobile Money payment prompt has been sent to ' . ($data['phone'] ?? 'your phone') . '. Approve it with your PIN.',
        ]);
    }

    /**
     * Build a human-readable description for the Marz transaction.
     */
    protected function paymentDescription(string $type): string
    {
        return match ($type) {
            Payment::TYPE_SUBSCRIPTION => 'UgaEats subscription',
            Payment::TYPE_GROUP_SHARE => 'UgaEats group order share',
            Payment::TYPE_RESERVATION => 'UgaEats reservation deposit',
            Payment::TYPE_SCHEDULED_ORDER => 'UgaEats scheduled order',
            Payment::TYPE_WALLET_DEPOSIT => 'Tasty Wallet top-up',
            default => 'UgaEats payment',
        };
    }

    /**
     * Mark a group order participant as paid following a successful payment.
     * If every participant with a non-zero share has paid, mark the group
     * order as closed (ready for the host to place the actual food order).
     */
    protected function markGroupSharePaid(Payment $payment): void
    {
        if (empty($payment->reference_id)) {
            return;
        }

        $participant = \App\Models\GroupOrderParticipant::find($payment->reference_id);
        if (!$participant) {
            return;
        }

        $participant->update([
            'has_paid' => true,
            'payment_tx_ref' => $payment->tx_ref,
            'paid_at' => now(),
        ]);

        $group = $participant->groupOrder()->first();
        if (!$group) {
            return;
        }

        // First successful payment: move OPEN → CLOSED so no new joiners /
        // cart edits can change the bill while others are still paying.
        if ($group->status === \App\Models\GroupOrder::STATUS_OPEN) {
            $group->update(['status' => \App\Models\GroupOrder::STATUS_CLOSED]);
            Log::info('Group order first share paid → closed', ['group_order_id' => $group->id]);
        }

        // Everyone with a non-zero share has paid: archive as ORDERED.
        if ($group->isFullyPaid() && in_array($group->status, [
            \App\Models\GroupOrder::STATUS_OPEN,
            \App\Models\GroupOrder::STATUS_CLOSED,
        ], true)) {
            $group->update(['status' => \App\Models\GroupOrder::STATUS_ORDERED]);
            Log::info('Group order fully paid → ordered', ['group_order_id' => $group->id]);
        }
    }
    
    /**
     * Handle payment callback (for card payments)
     */
    public function callback(Request $request)
    {
        $status = $request->get('status');
        $txRef = $request->get('tx_ref');
        $transactionId = $request->get('transaction_id');
        
        if ($txRef) {
            $payment = Payment::where('tx_ref', $txRef)->first();

            // If provider is Marz, verify via Marz API
            if ($payment && $payment->provider === 'marz') {
                $verification = $this->paymentService->verifyMarzTransaction($txRef);
                if ($verification['success'] || ($status === 'successful')) {
                    $payment->update([
                        'status' => 'successful',
                        'verified_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'verification' => $verification['data'] ?? null,
                        ]),
                    ]);

                    if ($payment->payment_type === 'subscription') {
                        $this->activateSubscription($payment);
                    }

                    if ($payment->payment_type === Payment::TYPE_RESERVATION && $payment->reference_id) {
                        return $this->finalizeReservationPayment($payment);
                    }

                    if ($payment->payment_type === Payment::TYPE_GROUP_SHARE && $payment->reference_id) {
                        $this->markGroupSharePaid($payment);
                        return redirect('/account/features#group-orders')
                            ->with('success', 'Your share has been paid. Thanks!');
                    }

                    if ($payment->payment_type === Payment::TYPE_SCHEDULED_ORDER && $payment->reference_id) {
                        $this->markScheduledOrderPaid($payment);
                        return redirect('/account/features#scheduled-orders')
                            ->with('success', 'Payment successful! Your scheduled order is confirmed.');
                    }

                    if ($payment->payment_type === Payment::TYPE_WALLET_DEPOSIT) {
                        $this->markWalletDepositPaid($payment);
                        return redirect('/account/wallet?topup=success')
                            ->with('success', 'Top-up successful! Your wallet has been credited.');
                    }

                    if ($payment->payment_type === 'order' && $payment->reference_id) {
                        $this->markOrderPaid($payment);
                    }

                    return redirect('/account/features#subscriptions')
                        ->with('success', 'Payment successful! Your subscription has been activated.');
                }

                if ($payment->payment_type === Payment::TYPE_RESERVATION && $payment->reference_id) {
                    return redirect('/reservation/success?id=' . $payment->reference_id
                        . '&tx=' . urlencode($txRef) . '&status=failed')
                        ->with('error', 'Payment was not completed. Your reservation has not been confirmed.');
                }
            }

            // Existing Flutterwave/card flow
            if ($status === 'successful' && $transactionId) {
                // Verify the transaction
                $verification = $this->paymentService->verifyTransaction($transactionId);

                if ($verification['success']) {
                    // Update payment record
                    $payment = Payment::where('tx_ref', $txRef)->first();

                    if ($payment) {
                        $payment->update([
                            'status' => 'successful',
                            'flw_ref' => $verification['flw_ref'] ?? null,
                            'verified_at' => now(),
                            'metadata' => array_merge($payment->metadata ?? [], [
                                'verification' => $verification['data'],
                            ]),
                        ]);

                        // Handle subscription activation
                        if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                            $this->activateSubscription($payment);
                        }
                    }

                    return redirect('/account/features#subscriptions')
                        ->with('success', 'Payment successful! Your subscription has been activated.');
                }
            }
        }
        
        // Payment failed or was cancelled
        if ($txRef) {
            Payment::where('tx_ref', $txRef)
                ->where('status', '!=', 'successful')
                ->update(['status' => 'failed']);

            $failedPayment = Payment::where('tx_ref', $txRef)->first();
            if ($failedPayment && $failedPayment->payment_type === Payment::TYPE_RESERVATION
                && $failedPayment->reference_id) {
                return redirect('/reservation/success?id=' . $failedPayment->reference_id
                    . '&tx=' . urlencode($txRef) . '&status=failed')
                    ->with('error', 'Payment was not completed. Your reservation has not been confirmed.');
            }
        }

        return redirect('/account/features#subscriptions')
            ->with('error', 'Payment was not completed. Please try again.');
    }
    
    /**
     * Verify a payment
     */
    public function verify(Request $request): JsonResponse
    {
        $txRef = $request->get('tx_ref');
        
        if (!$txRef) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction reference required',
            ], 400);
        }
        
        $payment = Payment::where('tx_ref', $txRef)->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }
        
        // If already verified
        if ($payment->status === 'successful') {
            return response()->json([
                'success' => true,
                'status' => 'successful',
                'payment' => $payment,
            ]);
        }
        
        // Check with payment provider
        if ($payment->provider === 'flutterwave') {
            $verification = $this->paymentService->verifyByReference($txRef);
            
            if ($verification['success']) {
                $payment->update([
                    'status' => 'successful',
                    'verified_at' => now(),
                ]);
                
                // Handle subscription activation
                if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                    $this->activateSubscription($payment);
                }
                
                return response()->json([
                    'success' => true,
                    'status' => 'successful',
                    'message' => 'Payment verified successfully',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'status' => $verification['status'] ?? 'pending',
                'message' => 'Payment not yet completed',
            ]);
        }

        // Marz Wallet (MTN/Airtel mobile money or card hosted checkout)
        if ($payment->provider === 'marz') {
            $verification = $this->paymentService->verifyMarzTransaction($txRef);

            if (!empty($verification['success'])) {
                $payment->update([
                    'status' => 'successful',
                    'verified_at' => now(),
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'verification' => $verification['data'] ?? null,
                    ]),
                ]);

                if ($payment->payment_type === Payment::TYPE_SUBSCRIPTION) {
                    $this->activateSubscription($payment);
                } elseif ($payment->payment_type === Payment::TYPE_GROUP_SHARE && $payment->reference_id) {
                    $this->markGroupSharePaid($payment);
                } elseif ($payment->payment_type === Payment::TYPE_SCHEDULED_ORDER && $payment->reference_id) {
                    $this->markScheduledOrderPaid($payment);
                } elseif ($payment->payment_type === Payment::TYPE_RESERVATION && $payment->reference_id) {
                    \Illuminate\Support\Facades\DB::table('reservations')
                        ->where('reservation_id', $payment->reference_id)
                        ->update([
                            'fee_paid' => 1,
                            'fee_transaction_id' => $payment->tx_ref,
                            'fee_paid_at' => now(),
                            'updated_at' => now(),
                        ]);
                } elseif ($payment->payment_type === Payment::TYPE_WALLET_DEPOSIT) {
                    $this->markWalletDepositPaid($payment);
                } elseif ($payment->payment_type === 'order' && $payment->reference_id) {
                    $this->markOrderPaid($payment);
                }

                return response()->json([
                    'success' => true,
                    'status' => 'successful',
                    'message' => 'Payment verified successfully',
                ]);
            }

            $reportedStatus = strtolower($verification['status'] ?? 'pending');
            if (in_array($reportedStatus, ['failed', 'cancelled'], true)) {
                $payment->update(['status' => $reportedStatus]);
            }

            return response()->json([
                'success' => false,
                'status' => $reportedStatus ?: 'pending',
                'message' => 'Payment not yet completed',
            ]);
        }

        // Demo payment - already successful
        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'payment' => $payment,
        ]);
    }
    
    /**
     * Get payment history for current customer
     */
    public function history(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $payments = Payment::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'tx_ref' => $payment->tx_ref,
                    'amount' => $payment->amount,
                    'formatted_amount' => 'UGX ' . number_format($payment->amount),
                    'type' => $payment->payment_type,
                    'method' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->format('M d, Y H:i'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'payments' => $payments,
        ]);
    }
    
    /**
     * Webhook handler for Flutterwave
     */
    public function webhook(Request $request): JsonResponse
    {
        // Verify webhook signature
        $signature = $request->header('verif-hash');
        $secretHash = env('FLUTTERWAVE_SECRET_HASH');
        
        if ($secretHash && $signature !== $secretHash) {
            Log::warning('Invalid webhook signature', ['signature' => $signature]);
            return response()->json(['status' => 'error'], 401);
        }
        
        $payload = $request->all();
        
        Log::info('Flutterwave webhook received', $payload);
        
        if (isset($payload['event']) && $payload['event'] === 'charge.completed') {
            $data = $payload['data'];
            $txRef = $data['tx_ref'] ?? null;
            
            if ($txRef) {
                $payment = Payment::where('tx_ref', $txRef)->first();
                
                if ($payment && $data['status'] === 'successful') {
                    $payment->update([
                        'status' => 'successful',
                        'flw_ref' => $data['flw_ref'] ?? null,
                        'verified_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'webhook_data' => $data,
                        ]),
                    ]);
                    
                    // Handle subscription activation
                    if ($payment->payment_type === 'subscription' && $payment->reference_id) {
                        $this->activateSubscription($payment);
                    }
                }
            }
        }
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Webhook handler for Marz Wallet (MarzPay)
     *
     * MarzPay sends payloads of the form:
     *   { "event_type": "collection.completed", "transaction": { "reference": "...", "status": "completed", ... }, "collection": { ... } }
     */
    public function webhookMarz(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Marz webhook received', $payload);

        $tx = $payload['transaction'] ?? [];
        $reference = $tx['reference']
            ?? ($payload['reference'] ?? ($payload['data']['reference'] ?? null));
        $status = strtolower($tx['status']
            ?? ($payload['status'] ?? ($payload['data']['status'] ?? '')));
        $eventType = strtolower($payload['event_type'] ?? '');

        if ($reference) {
            $payment = Payment::where('tx_ref', $reference)->first();
            if ($payment) {
                $isSuccess = in_array($status, ['successful', 'completed', 'success'], true)
                    || $eventType === 'collection.completed';

                if ($isSuccess) {
                    $payment->update([
                        'status' => 'successful',
                        'verified_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], ['webhook' => $payload]),
                    ]);

                    if ($payment->payment_type === 'subscription') {
                        $this->activateSubscription($payment);
                    } elseif ($payment->payment_type === 'order' && $payment->reference_id) {
                        $this->markOrderPaid($payment);
                    } elseif ($payment->payment_type === Payment::TYPE_SCHEDULED_ORDER && $payment->reference_id) {
                        $this->markScheduledOrderPaid($payment);
                    } elseif ($payment->payment_type === Payment::TYPE_RESERVATION && $payment->reference_id) {
                        \Illuminate\Support\Facades\DB::table('reservations')
                            ->where('reservation_id', $payment->reference_id)
                            ->update([
                                'fee_paid' => 1,
                                'fee_transaction_id' => $payment->tx_ref,
                                'fee_paid_at' => now(),
                                'updated_at' => now(),
                            ]);
                        Log::info('Reservation fee paid via Marz webhook', [
                            'reservation_id' => $payment->reference_id,
                            'tx_ref' => $payment->tx_ref,
                        ]);
                    } elseif ($payment->payment_type === Payment::TYPE_GROUP_SHARE && $payment->reference_id) {
                        $this->markGroupSharePaid($payment);
                    } elseif ($payment->payment_type === Payment::TYPE_WALLET_DEPOSIT) {
                        $this->markWalletDepositPaid($payment);
                    }
                } elseif (in_array($status, ['failed', 'cancelled'], true) || $eventType === 'collection.failed') {
                    $payment->update([
                        'status' => $status === 'cancelled' ? 'cancelled' : 'failed',
                        'metadata' => array_merge($payment->metadata ?? [], ['webhook' => $payload]),
                    ]);

                    if ($payment->payment_type === Payment::TYPE_RESERVATION && $payment->reference_id) {
                        // Roll back the unpaid reservation so the slot is freed.
                        \Illuminate\Support\Facades\DB::table('reservations')
                            ->where('reservation_id', $payment->reference_id)
                            ->where('fee_paid', 0)
                            ->delete();
                    }

                    if ($payment->payment_type === Payment::TYPE_WALLET_DEPOSIT) {
                        \App\Models\WalletTransaction::where('reference', $payment->tx_ref)
                            ->where('status', 'pending')
                            ->update(['status' => 'failed']);
                    }
                }
            } else {
                Log::warning('Marz webhook: no matching payment for reference', ['reference' => $reference]);
            }
        }

        return response()->json(['status' => 'success']);
    }
    
    /**
     * Mark a TastyIgniter order as paid following a successful Marz collection.
     */
    protected function markOrderPaid(Payment $payment): void
    {
        try {
            $orderModel = '\\Igniter\\Cart\\Models\\Order';
            if (!class_exists($orderModel)) {
                Log::warning('Order model not found while marking paid', ['payment_id' => $payment->id]);
                return;
            }

            $order = $orderModel::find($payment->reference_id);
            if (!$order) {
                Log::warning('Order not found for Marz payment', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->reference_id,
                ]);
                return;
            }

            if (method_exists($order, 'logPaymentAttempt')) {
                $order->logPaymentAttempt('Marz payment confirmed via webhook', 1, [], $payment->metadata ?? [], true);
            }
            if (method_exists($order, 'markAsPaymentProcessed')) {
                $order->markAsPaymentProcessed();
            }

            // Move to configured "paid" status if the gateway record has one
            $paymentRow = \DB::table('payments')->where('code', 'marz')->first();
            $orderStatus = $paymentRow && !empty($paymentRow->data)
                ? (json_decode($paymentRow->data, true)['order_status'] ?? null)
                : null;
            if ($orderStatus && method_exists($order, 'updateOrderStatus')) {
                $order->updateOrderStatus((int) $orderStatus, ['notify' => false]);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to mark order paid from Marz webhook', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
        }
    }

    /**
     * Mark a reservation's commitment fee as paid after Marz confirms the payment.
     */
    protected function finalizeReservationPayment(Payment $payment)
    {
        $reservationId = (int) $payment->reference_id;

        \Illuminate\Support\Facades\DB::table('reservations')
            ->where('reservation_id', $reservationId)
            ->update([
                'fee_paid' => 1,
                'fee_transaction_id' => $payment->tx_ref,
                'fee_paid_at' => now(),
                'updated_at' => now(),
            ]);

        Log::info('Reservation commitment fee marked paid', [
            'reservation_id' => $reservationId,
            'payment_id' => $payment->id,
            'tx_ref' => $payment->tx_ref,
        ]);

        return redirect('/reservation/success?id=' . $reservationId
            . '&tx=' . urlencode($payment->tx_ref) . '&status=paid')
            ->with('success', 'Reservation confirmed! Your commitment fee has been paid.');
    }

    /**
     * Activate subscription after successful payment.
     *
     * If reference_id points to an existing Subscription row, just activate it.
     * If not (pay-first flow), create the Subscription from plan_id stored in
     * Payment.metadata and link it back via reference_id.
     */
    protected function activateSubscription(Payment $payment): void
    {
        $subscription = $payment->reference_id ? Subscription::find($payment->reference_id) : null;

        if (!$subscription) {
            // Pay-first flow: build the subscription from metadata.
            $planId = $payment->metadata['plan_id'] ?? null;
            $customerId = $payment->customer_id;
            if (!$planId || !$customerId) {
                Log::warning('activateSubscription: cannot create subscription (missing plan_id/customer_id)', [
                    'payment_id' => $payment->id,
                ]);
                return;
            }

            $plan = \App\Models\SubscriptionPlan::find($planId);
            if (!$plan) {
                Log::warning('activateSubscription: plan not found', ['plan_id' => $planId]);
                return;
            }

            $expiresAt = match ($plan->billing_period) {
                'weekly' => now()->addWeek(),
                'monthly' => now()->addMonth(),
                'yearly' => now()->addYear(),
                default => now()->addMonth(),
            };

            $subscription = Subscription::create([
                'customer_id' => $customerId,
                'plan_id' => $plan->id,
                'status' => Subscription::STATUS_ACTIVE,
                'started_at' => now(),
                'expires_at' => $expiresAt,
                'meals_remaining' => $plan->meals_per_period ?? 0,
                'meals_used' => 0,
                'amount_paid' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_reference' => $payment->tx_ref,
                'auto_renew' => (bool) ($payment->metadata['auto_renew'] ?? true),
                'delivery_address' => $payment->metadata['delivery_address'] ?? null,
                'preferred_delivery_time' => $payment->metadata['preferred_delivery_time'] ?? null,
            ]);

            $payment->update(['reference_id' => $subscription->id]);

            Log::info('Subscription created on payment success', [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
            ]);
            return;
        }

        if ($subscription->status !== Subscription::STATUS_ACTIVE) {
            $subscription->update([
                'status' => Subscription::STATUS_ACTIVE,
                'started_at' => $subscription->started_at ?? now(),
                'amount_paid' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_reference' => $payment->tx_ref,
            ]);

            Log::info('Subscription activated', [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
            ]);
        }
    }

    /**
     * Confirm a scheduled order after Marz reports the payment captured.
     */
    protected function markScheduledOrderPaid(Payment $payment): void
    {
        if (empty($payment->reference_id)) {
            return;
        }

        $scheduledOrder = \App\Models\ScheduledOrder::find($payment->reference_id);
        if (!$scheduledOrder) {
            Log::warning('ScheduledOrder not found for Marz payment', [
                'payment_id' => $payment->id,
                'reference_id' => $payment->reference_id,
            ]);
            return;
        }

        $cart = $scheduledOrder->cart_data ?? [];
        $cart['payment'] = [
            'tx_ref' => $payment->tx_ref,
            'amount' => (float) $payment->amount,
            'method' => $payment->payment_method,
            'paid_at' => now()->toIso8601String(),
        ];

        $scheduledOrder->update([
            'status' => \App\Models\ScheduledOrder::STATUS_CONFIRMED,
            'cart_data' => $cart,
        ]);

        Log::info('Scheduled order confirmed after Marz payment', [
            'scheduled_order_id' => $scheduledOrder->id,
            'payment_id' => $payment->id,
        ]);
    }

    /**
     * Credit a pending Tasty Wallet deposit after Marz confirms the payment.
     * Looks up the pending WalletTransaction by reference == payment.tx_ref,
     * marks it completed and updates the wallet balance. Idempotent: returns
     * early if the transaction is already completed.
     */
    protected function markWalletDepositPaid(Payment $payment): void
    {
        $transaction = \App\Models\WalletTransaction::where('reference', $payment->tx_ref)->first();
        if (!$transaction) {
            Log::warning('Wallet deposit completion: no matching wallet transaction', [
                'payment_id' => $payment->id,
                'tx_ref' => $payment->tx_ref,
            ]);
            return;
        }

        if ($transaction->status === 'completed') {
            return;
        }

        $wallet = $transaction->wallet;
        if (!$wallet) {
            Log::warning('Wallet deposit completion: no wallet on transaction', [
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $balanceBefore = (float) $wallet->balance;
        $balanceAfter = $balanceBefore + (float) $transaction->amount;

        $wallet->update(['balance' => $balanceAfter]);
        $transaction->update([
            'status' => 'completed',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'external_reference' => $payment->tx_ref,
        ]);

        Log::info('Tasty Wallet credited after Marz deposit', [
            'wallet_id' => $wallet->id,
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'new_balance' => $balanceAfter,
        ]);
    }
    
    /**
     * Create subscription from successful payment
     */
    protected function createSubscriptionFromPayment(Payment $payment, Request $request): void
    {
        $planId = $request->input('plan_id') ?? ($payment->metadata['plan_id'] ?? null);
        $customerId = $payment->customer_id;
        
        if (!$planId || !$customerId) {
            Log::warning('Cannot create subscription: missing plan_id or customer_id', [
                'plan_id' => $planId,
                'customer_id' => $customerId,
            ]);
            return;
        }
        
        // Check if customer already has active subscription
        $existingSubscription = Subscription::where('customer_id', $customerId)
            ->where('status', 'active')
            ->first();
            
        if ($existingSubscription) {
            Log::info('Customer already has active subscription', [
                'customer_id' => $customerId,
                'existing_subscription_id' => $existingSubscription->id,
            ]);
            return;
        }
        
        // Get the plan
        $plan = \App\Models\SubscriptionPlan::find($planId);
        
        if (!$plan) {
            Log::warning('Plan not found for subscription creation', ['plan_id' => $planId]);
            return;
        }
        
        // Calculate expiration based on billing period
        $expiresAt = match($plan->billing_period) {
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };
        
        // Create the subscription with correct table columns
        $subscription = Subscription::create([
            'customer_id' => $customerId,
            'plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => $expiresAt,
            'meals_remaining' => $plan->meals_per_period ?? 0,
            'meals_used' => 0,
            'amount_paid' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_reference' => $payment->tx_ref,
        ]);
        
        // Update payment with subscription reference
        $payment->update(['reference_id' => $subscription->id]);
        
        Log::info('Subscription created from payment', [
            'subscription_id' => $subscription->id,
            'plan_id' => $plan->id,
            'customer_id' => $customerId,
            'payment_id' => $payment->id,
        ]);
    }
    
    /**
     * Get current customer ID
     */
    protected function getCustomerId(): ?int
    {
        // Check for TastyIgniter customer session first (most common case)
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }
        
        // Fallback to Laravel Auth
        if (Auth::check()) {
            return Auth::id();
        }
        
        return null;
    }
}
