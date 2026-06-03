<?php

namespace App\Http\Controllers;

use App\Models\TastyWallet;
use App\Models\WalletTransaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Get wallet balance and info
     */
    public function balance(): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customerId);

        return response()->json([
            'success' => true,
            'wallet' => [
                'id' => $wallet->id,
                'balance' => $wallet->balance,
                'formatted_balance' => $wallet->getFormattedBalance(),
                'currency' => $wallet->currency,
                'is_active' => $wallet->is_active,
            ],
        ]);
    }

    /**
     * Get wallet transaction history
     */
    public function transactions(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customerId);

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->limit($request->input('limit', 20))
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'formatted_amount' => $transaction->getFormattedAmount(),
                    'description' => $transaction->description,
                    'reference' => $transaction->reference,
                    'status' => $transaction->status,
                    'icon' => $transaction->getIcon(),
                    'color_class' => $transaction->getColorClass(),
                    'created_at' => $transaction->created_at->format('M d, Y H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Topup wallet
     */
    public function topup(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5000|max:5000000',
            'payment_method' => 'required|in:mtn,airtel,card',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $wallet = TastyWallet::getOrCreateForCustomer($customerId);

        // Get customer details
        $customer = \Igniter\User\Facades\Auth::customer();
        $email = $customer ? $customer->email : 'customer@ugaeats.com';
        $name = $customer ? $customer->full_name : 'Customer';

        try {
            // Create pending transaction
            $txRef = 'TOPUP-' . $wallet->id . '-' . time();
            
            $pendingTransaction = $wallet->transactions()->create([
                'type' => 'credit',
                'amount' => $data['amount'],
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance + $data['amount'],
                'description' => 'Wallet Top-up',
                'reference' => $txRef,
                'reference_type' => 'topup',
                'status' => 'pending',
            ]);

            // Process payment based on test mode
            if ($this->paymentService->isTestMode()) {
                // Simulate successful payment
                $result = $this->paymentService->simulatePayment([
                    'amount' => $data['amount'],
                    'email' => $email,
                    'type' => 'wallet_topup',
                ]);

                // Credit the wallet
                $wallet->balance += $data['amount'];
                $wallet->save();

                // Update transaction status
                $pendingTransaction->update([
                    'status' => 'completed',
                    'balance_after' => $wallet->balance,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Top-up successful! (Demo Mode)',
                    'tx_ref' => $result['tx_ref'],
                    'new_balance' => $wallet->getFormattedBalance(),
                ]);
            }

            // Real payment processing
            $paymentData = [
                'amount' => $data['amount'],
                'email' => $email,
                'name' => $name,
                'phone' => $data['phone'],
                'payment_options' => $data['payment_method'] === 'card' ? 'card' : 'mobilemoneyuganda',
                'tx_ref' => $txRef,
                'redirect_url' => url('/account/wallet?topup=success'),
            ];

            if ($data['payment_method'] === 'card') {
                $response = $this->paymentService->initializePayment($paymentData);
                
                if (isset($response['data']['link'])) {
                    return response()->json([
                        'success' => true,
                        'redirect_url' => $response['data']['link'],
                        'tx_ref' => $txRef,
                    ]);
                }
            } else {
                // Mobile money payment
                $response = $this->paymentService->chargeMobileMoney([
                    'amount' => $data['amount'],
                    'email' => $email,
                    'phone' => $data['phone'],
                    'tx_ref' => $txRef,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment request sent. Please check your phone to approve.',
                    'tx_ref' => $txRef,
                    'status' => 'pending',
                ]);
            }

            throw new \Exception('Payment initialization failed');
        } catch (\Exception $e) {
            Log::error('Wallet topup error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Top-up failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify topup payment
     */
    public function verifyTopup(Request $request): JsonResponse
    {
        $txRef = $request->input('tx_ref');

        if (!$txRef) {
            return response()->json(['error' => 'Transaction reference required'], 400);
        }

        try {
            // Find the pending transaction
            $transaction = WalletTransaction::where('reference', $txRef)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Verify with Flutterwave
            $verification = $this->paymentService->verifyTransaction($txRef);

            if ($verification['status'] === 'successful') {
                $wallet = $transaction->wallet;
                $wallet->balance += $transaction->amount;
                $wallet->save();

                $transaction->update([
                    'status' => 'completed',
                    'balance_after' => $wallet->balance,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Top-up completed!',
                    'new_balance' => $wallet->getFormattedBalance(),
                ]);
            }

            $transaction->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Topup verification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
            ], 500);
        }
    }

    /**
     * Get current customer ID from TastyIgniter auth
     */
    protected function getCustomerId(): ?int
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }

        return null;
    }

    /**
     * Top up the Tasty Wallet through MarzPay.
     *
     * Creates a `pending` wallet transaction up front, delegates the actual
     * charge to PaymentController::initialize (which calls Marz collect-money)
     * and stamps the wallet transaction with the returned tx_ref so the
     * webhook / verify endpoints can credit the balance on success.
     */
    public function deposit(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Please log in to deposit'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:5000000',
            'payment_method' => 'required|in:mtn,airtel,card,mobilemoney,flutterwave',
            'phone' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'provider' => 'nullable|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $wallet = TastyWallet::getOrCreateForCustomer($customerId);
            $amount = floatval($request->input('amount'));

            // Normalise legacy payload shapes from the existing UIs.
            $method = $request->input('payment_method');
            if ($method === 'mobilemoney') {
                $method = $request->input('provider', 'mtn');
            } elseif ($method === 'flutterwave') {
                $method = 'card';
            }
            $phone = $request->input('phone') ?? $request->input('phone_number');

            $customer = class_exists(\Igniter\User\Facades\Auth::class)
                ? \Igniter\User\Facades\Auth::customer() : null;
            $email = $customer ? $customer->email : 'customer@ugaeats.com';
            $name = $customer ? trim($customer->full_name ?? $customer->first_name ?? 'Customer') : 'Customer';

            $description = in_array($method, ['mtn', 'airtel'], true)
                ? 'Wallet top-up via ' . strtoupper($method) . ' Mobile Money'
                : 'Wallet top-up via Card';

            // Hold a pending row so we have somewhere to record the eventual
            // credit. The `reference` is filled in once Marz issues a tx_ref.
            $pending = $wallet->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'reference' => TastyWallet::generateReference('DEP-PEND'),
                'description' => $description,
                'payment_method' => $method,
                'status' => 'pending',
            ]);

            // Delegate to PaymentController::initialize so we reuse the Marz
            // collect-money + callback/webhook plumbing already in place.
            $sub = Request::create('/payment/initialize', 'POST', [
                'amount' => $amount,
                'email' => $email,
                'phone' => $phone,
                'name' => $name,
                'type' => \App\Models\Payment::TYPE_WALLET_DEPOSIT,
                'payment_method' => $method,
            ]);

            $jsonResponse = app(\App\Http\Controllers\PaymentController::class)->initialize($sub);
            $body = json_decode($jsonResponse->getContent(), true) ?: [];

            if (empty($body['success'])) {
                $pending->update(['status' => 'failed']);
                return response()->json([
                    'error' => $body['message'] ?? 'Top-up initialization failed',
                ], 400);
            }

            // Stamp our pending row with the real tx_ref returned by Marz so the
            // webhook / verify endpoints can find it later.
            $pending->update(['reference' => $body['tx_ref']]);

            // In demo/test mode PaymentController already runs markWalletDepositPaid
            // synchronously; reload the wallet so the caller sees the new balance.
            $wallet->refresh();

            return response()->json([
                'success' => true,
                'message' => $body['message'] ?? 'Top-up initiated.',
                'tx_ref' => $body['tx_ref'],
                'payment_id' => $body['payment_id'] ?? null,
                'redirect_url' => $body['redirect_url'] ?? null,
                'pending' => !empty($body['pending']),
                'wallet' => [
                    'balance' => $wallet->balance,
                    'formatted_balance' => $wallet->getFormattedBalance(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet deposit error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Withdraw funds from the Tasty Wallet to a mobile-money number.
     *
     * The wallet is debited immediately to prevent double-spend. We then
     * attempt a Marz payout (`/send-money`). If Marz confirms instantly the
     * transaction is marked completed; if it accepts the request but reports
     * pending we leave the transaction pending for the operator to confirm.
     * If Marz is not configured we keep the debit and mark the transaction
     * `pending` for manual fulfilment so the user's balance stays consistent
     * and an admin can complete the payout out-of-band. On any hard failure
     * we refund the wallet automatically.
     */
    public function withdraw(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Please log in to withdraw'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:1000000',
            'phone_number' => 'required|string',
            'provider' => 'required|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customerId);
        $amount = floatval($request->input('amount'));

        if (!$wallet->hasSufficientBalance($amount)) {
            return response()->json(['error' => 'Insufficient wallet balance'], 422);
        }

        $provider = $request->input('provider');
        $rawPhone = ltrim($request->input('phone_number'), '+');
        $phone = str_starts_with($rawPhone, '256') ? '+' . $rawPhone : '+256' . ltrim($rawPhone, '0');
        $reference = TastyWallet::generateReference('WTH');

        // Debit immediately so the user can't double-spend while the payout
        // is in flight. We'll refund automatically on hard failure.
        try {
            $debit = $wallet->withdraw(
                $amount,
                $provider . '_mobilemoney',
                $phone,
                'Withdrawal to ' . $phone . ' via ' . strtoupper($provider)
            );
            // Override the auto-completed status from the model helper: the
            // payout isn't really completed until Marz confirms.
            $debit->update([
                'status' => 'pending',
                'reference' => $reference,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        try {
            $payout = $this->paymentService->sendMarzPayout([
                'amount' => $amount,
                'phone' => $phone,
                'reference' => $reference,
                'description' => 'UgaEats wallet withdrawal to ' . strtoupper($provider),
            ]);

            // No Marz credentials → keep the debit as pending for manual
            // fulfilment by an admin (the wallet is already debited).
            if (empty($payout['configured'])) {
                $debit->update([
                    'status' => 'pending',
                    'metadata' => array_merge((array) $debit->metadata, [
                        'requires_manual_payout' => true,
                    ]),
                ]);
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'message' => 'Withdrawal request received. Funds will be sent to ' . $phone . ' within 24 hours.',
                    'transaction' => [
                        'reference' => $debit->reference,
                        'amount' => $debit->amount,
                    ],
                    'wallet' => [
                        'balance' => $wallet->fresh()->balance,
                        'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
                    ],
                ]);
            }

            // Hard failure from Marz → refund the wallet and mark failed.
            if (empty($payout['success'])) {
                $wallet->refresh();
                $wallet->update(['balance' => $wallet->balance + $amount]);
                $debit->update([
                    'status' => 'failed',
                    'balance_after' => $wallet->balance,
                    'metadata' => array_merge((array) $debit->metadata, [
                        'payout_error' => $payout['message'] ?? 'Unknown error',
                    ]),
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $payout['message'] ?? 'Withdrawal failed. Your balance has been restored.',
                ], 502);
            }

            // Marz accepted the payout. Mark completed if it reported success
            // synchronously, otherwise keep pending and rely on the operator
            // / reconciliation to flip it later.
            $debit->update([
                'status' => !empty($payout['completed']) ? 'completed' : 'pending',
                'external_reference' => $phone,
                'metadata' => array_merge((array) $debit->metadata, [
                    'marz' => $payout['data'] ?? null,
                    'marz_tx_ref' => $payout['tx_ref'] ?? null,
                ]),
            ]);

            return response()->json([
                'success' => true,
                'status' => $debit->status,
                'message' => 'Withdrawal of UGX ' . number_format($amount) . ' to ' . $phone . ' '
                    . ($debit->status === 'completed' ? 'completed!' : 'is being processed.'),
                'transaction' => [
                    'reference' => $debit->reference,
                    'amount' => $debit->amount,
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet withdraw error: ' . $e->getMessage());
            // Refund on unexpected failure.
            $wallet->refresh();
            $wallet->update(['balance' => $wallet->balance + $amount]);
            $debit->update(['status' => 'failed', 'balance_after' => $wallet->balance]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Pay for an order using wallet balance
     */
    public function payOrder(int $orderId, float $amount): array
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return ['success' => false, 'error' => 'Not authenticated'];
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customerId);

        if (!$wallet->hasSufficientBalance($amount)) {
            return ['success' => false, 'error' => 'Insufficient wallet balance'];
        }

        try {
            $transaction = $wallet->pay(
                $amount,
                'order',
                $orderId,
                'Payment for Order #' . $orderId
            );

            // Add 5% cashback
            $cashback = $amount * 0.05;
            $wallet->addCashback($cashback, $orderId, 'Cashback for Order #' . $orderId);

            return [
                'success' => true,
                'transaction' => $transaction,
                'cashback' => $cashback,
                'new_balance' => $wallet->fresh()->balance,
            ];
        } catch (\Exception $e) {
            Log::error('Wallet payment error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
