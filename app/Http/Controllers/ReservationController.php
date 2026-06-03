<?php

namespace App\Http\Controllers;

use App\Models\TastyWallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Commitment fee per guest, in UGX.
     */
    const FEE_PER_GUEST = 20000;

    /**
     * Minimum number of completed orders for a customer to count as "trusted"
     * and be allowed to skip online payment (Pay at Venue / Cash).
     */
    const TRUSTED_ORDERS_THRESHOLD = 5;

    /**
     * Compute the commitment fee from the guest count.
     */
    public static function feeForGuests(int $guests): int
    {
        $guests = max(1, $guests);
        return $guests * self::FEE_PER_GUEST;
    }

    /**
     * A "trusted" customer can skip online deposit and pay at the venue.
     */
    public static function isTrustedCustomer($customer): bool
    {
        if (!$customer) {
            return false;
        }

        $customerId = is_object($customer) ? ($customer->customer_id ?? null) : (int) $customer;
        if (!$customerId) {
            return false;
        }

        try {
            $completedStatus = (int) (setting('completed_order_status') ?? 0);
        } catch (\Throwable $e) {
            $completedStatus = 0;
        }

        $query = DB::table('orders')->where('customer_id', $customerId);
        if ($completedStatus > 0) {
            $query->where('status_id', $completedStatus);
        } else {
            // Fallback: any non-cancelled order with payment recorded
            $query->where('processed', 1);
        }

        return $query->count() >= self::TRUSTED_ORDERS_THRESHOLD;
    }

    /**
     * Create a reservation with a commitment fee.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $customer = \Igniter\User\Facades\Auth::customer();
            $customerId = $customer ? $customer->customer_id : null;
            $isTrusted = self::isTrustedCustomer($customer);

            $allowedMethods = ['wallet', 'mtn', 'airtel', 'marz'];
            if ($isTrusted) {
                $allowedMethods[] = 'cash_at_venue';
            }

            $validated = $request->validate([
                'location_id' => 'required|integer',
                'date' => 'required|date',
                'time' => 'required',
                'guest' => 'required|integer|min:1|max:20',
                'first_name' => 'required|string|max:255',
                'email' => 'required|email',
                'telephone' => 'required|string',
                'comment' => 'nullable|string',
                'payment_method' => 'required|in:' . implode(',', $allowedMethods),
            ]);

            $commitmentFee = self::feeForGuests((int) $validated['guest']);

            // Wallet eligibility: must be logged in and have balance.
            if ($validated['payment_method'] === 'wallet') {
                if (!$customerId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please login to pay with wallet',
                    ], 401);
                }

                $wallet = TastyWallet::getOrCreateForCustomer($customerId);
                if (!$wallet->hasSufficientBalance($commitmentFee)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient wallet balance. You need UGX ' . number_format($commitmentFee)
                            . ' but have UGX ' . number_format($wallet->balance),
                    ], 400);
                }
            }

            // Create the reservation row (pending until payment confirmed).
            $reservationData = [
                'location_id' => $validated['location_id'],
                'table_id' => 0,
                'guest_num' => $validated['guest'],
                'customer_id' => $customerId,
                'first_name' => $validated['first_name'],
                'last_name' => '',
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'comment' => $validated['comment'] ?? '',
                'reserve_date' => $validated['date'],
                'reserve_time' => $validated['time'],
                'commitment_fee' => $commitmentFee,
                'fee_paid' => 0,
                'fee_payment_method' => $validated['payment_method'],
                'status_id' => 1, // Pending
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent() ?? '',
                'hash' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $reservationId = DB::table('reservations')->insertGetId($reservationData);

            // -------- Wallet (in-app credit) --------
            if ($validated['payment_method'] === 'wallet') {
                $wallet = TastyWallet::getOrCreateForCustomer($customerId);
                $transaction = $wallet->pay(
                    $commitmentFee,
                    "Reservation #{$reservationId} commitment fee"
                );

                if (!$transaction) {
                    DB::table('reservations')->where('reservation_id', $reservationId)->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Wallet charge failed. Please try again.',
                    ], 400);
                }

                $transactionId = 'WAL-' . $transaction->id;
                $this->markReservationPaid($reservationId, $transactionId);

                return $this->reservationConfirmedResponse($reservationId, $validated, $commitmentFee, $transactionId);
            }

            // -------- Cash at Venue (trusted customers only) --------
            if ($validated['payment_method'] === 'cash_at_venue') {
                $transactionId = 'VENUE-' . str_pad((string) $reservationId, 6, '0', STR_PAD_LEFT);
                // We mark the booking as confirmed; fee_paid stays 0 because the
                // commitment fee is still owed and will be collected at the venue.
                DB::table('reservations')
                    ->where('reservation_id', $reservationId)
                    ->update([
                        'fee_transaction_id' => $transactionId,
                        'updated_at' => now(),
                    ]);

                return $this->reservationConfirmedResponse(
                    $reservationId,
                    $validated,
                    $commitmentFee,
                    $transactionId,
                    'Reservation confirmed. Please pay the commitment fee on arrival.'
                );
            }

            // -------- Marz Wallet (mtn / airtel mobile money, or marz card) --------
            $apiMethod = in_array($validated['payment_method'], ['mtn', 'airtel'], true)
                ? 'mobile_money'
                : 'card';

            $paymentService = new \App\Services\PaymentService();
            $init = $paymentService->initializeMarzPayment([
                'amount' => $commitmentFee,
                'currency' => 'UGX',
                'country' => 'UG',
                'method' => $apiMethod,
                'email' => $validated['email'],
                'phone' => $apiMethod === 'mobile_money' ? $validated['telephone'] : null,
                'name' => $validated['first_name'],
                'description' => "Reservation #{$reservationId} commitment fee",
                'redirect_url' => route('payment.callback'),
                'meta' => [
                    'type' => 'reservation',
                    'reservation_id' => $reservationId,
                ],
            ]);

            if (empty($init['success'])) {
                // Marz initialization failed — keep no orphan reservation.
                DB::table('reservations')->where('reservation_id', $reservationId)->delete();

                return response()->json([
                    'success' => false,
                    'message' => $init['message'] ?? 'Payment initialization failed. Please try again.',
                ], 400);
            }

            // Record the pending payment so the callback can finalize it.
            try {
                \App\Models\Payment::create([
                    'customer_id' => $customerId,
                    'tx_ref' => $init['tx_ref'] ?? null,
                    'amount' => $commitmentFee,
                    'currency' => 'UGX',
                    'payment_method' => $validated['payment_method'],
                    'payment_type' => \App\Models\Payment::TYPE_RESERVATION,
                    'reference_id' => $reservationId,
                    'status' => \App\Models\Payment::STATUS_PENDING,
                    'provider' => 'marz',
                    'metadata' => [
                        'reservation_id' => $reservationId,
                        'reservation_hash' => $reservationData['hash'],
                        'api_method' => $apiMethod,
                        'phone' => $validated['telephone'],
                        'transaction_uuid' => $init['transaction_uuid'] ?? null,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to record pending Marz reservation payment', ['error' => $e->getMessage()]);
            }

            DB::table('reservations')
                ->where('reservation_id', $reservationId)
                ->update([
                    'fee_transaction_id' => $init['tx_ref'] ?? null,
                    'updated_at' => now(),
                ]);

            // Card flow → Marz returned a hosted checkout URL.
            if (!empty($init['link'])) {
                return response()->json([
                    'success' => true,
                    'redirect' => true,
                    'redirect_url' => $init['link'],
                    'reservation' => [
                        'id' => $reservationId,
                        'date' => $validated['date'],
                        'time' => $validated['time'],
                        'guests' => $validated['guest'],
                        'commitment_fee' => $commitmentFee,
                        'transaction_id' => $init['tx_ref'] ?? null,
                    ],
                ]);
            }

            // Mobile-money flow → USSD push sent to the customer's phone.
            // Reservation stays pending until the Marz webhook/callback confirms.
            return response()->json([
                'success' => true,
                'pending' => true,
                'message' => 'A Mobile Money payment prompt has been sent to '
                    . $validated['telephone']
                    . '. Approve it with your PIN to confirm the reservation.',
                'redirect_url' => '/reservation/success?id=' . $reservationId
                    . '&tx=' . urlencode((string) ($init['tx_ref'] ?? ''))
                    . '&status=pending',
                'reservation' => [
                    'id' => $reservationId,
                    'date' => $validated['date'],
                    'time' => $validated['time'],
                    'guests' => $validated['guest'],
                    'commitment_fee' => $commitmentFee,
                    'transaction_id' => $init['tx_ref'] ?? null,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Invalid input',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reservation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark a reservation's commitment fee as paid.
     */
    protected function markReservationPaid(int $reservationId, string $transactionId): void
    {
        DB::table('reservations')
            ->where('reservation_id', $reservationId)
            ->update([
                'fee_paid' => 1,
                'fee_transaction_id' => $transactionId,
                'fee_paid_at' => now(),
                'updated_at' => now(),
            ]);
    }

    /**
     * Build a standard "reservation confirmed" JSON response.
     */
    protected function reservationConfirmedResponse(
        int $reservationId,
        array $validated,
        int $commitmentFee,
        string $transactionId,
        ?string $message = null
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message ?? 'Reservation confirmed! Your commitment fee has been paid.',
            'reservation' => [
                'id' => $reservationId,
                'date' => $validated['date'],
                'time' => $validated['time'],
                'guests' => $validated['guest'],
                'commitment_fee' => $commitmentFee,
                'transaction_id' => $transactionId,
            ],
        ]);
    }

    /**
     * Get commitment fee for a given guest count (and customer trust info).
     */
    public function getFee(Request $request): JsonResponse
    {
        $guests = max(1, (int) $request->query('guests', 1));
        $fee = self::feeForGuests($guests);

        $customer = \Igniter\User\Facades\Auth::customer();
        $walletBalance = 0;
        if ($customer) {
            $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
            $walletBalance = $wallet->balance;
        }

        return response()->json([
            'success' => true,
            'guests' => $guests,
            'fee_per_guest' => self::FEE_PER_GUEST,
            'commitment_fee' => $fee,
            'formatted_fee' => 'UGX ' . number_format($fee),
            'wallet_balance' => $walletBalance,
            'formatted_wallet_balance' => 'UGX ' . number_format($walletBalance),
            'can_pay_with_wallet' => $walletBalance >= $fee,
            'is_logged_in' => $customer !== null,
            'is_trusted' => self::isTrustedCustomer($customer),
        ]);
    }

    /**
     * Check reservation status. If the reservation is still unpaid and has a
     * Marz tx_ref, actively poll Marz to update the status — this keeps the
     * flow working even when the Marz webhook can't reach the server (e.g. in
     * local dev) and acts as a safety net in production.
     */
    public function status(Request $request, $id): JsonResponse
    {
        $reservation = DB::table('reservations')
            ->where('reservation_id', $id)
            ->first();

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        if (!$reservation->fee_paid && $reservation->fee_transaction_id) {
            $payment = \App\Models\Payment::where('tx_ref', $reservation->fee_transaction_id)->first();
            if ($payment && $payment->provider === 'marz' && $payment->status !== 'successful') {
                try {
                    $verification = (new \App\Services\PaymentService())
                        ->verifyMarzTransaction($reservation->fee_transaction_id);

                    if (!empty($verification['success'])) {
                        $payment->update([
                            'status' => 'successful',
                            'verified_at' => now(),
                            'metadata' => array_merge($payment->metadata ?? [], [
                                'verification' => $verification['data'] ?? null,
                            ]),
                        ]);
                        DB::table('reservations')
                            ->where('reservation_id', $id)
                            ->update([
                                'fee_paid' => 1,
                                'fee_paid_at' => now(),
                                'updated_at' => now(),
                            ]);
                        $reservation = DB::table('reservations')->where('reservation_id', $id)->first();
                    } elseif (in_array(strtolower($verification['status'] ?? ''), ['failed', 'cancelled'], true)) {
                        $payment->update(['status' => strtolower($verification['status'])]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Marz verification poll failed', [
                        'reservation_id' => $id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $payment = \App\Models\Payment::where('tx_ref', $reservation->fee_transaction_id ?? '')->first();
        $paymentStatus = $payment->status ?? ($reservation->fee_paid ? 'successful' : 'pending');

        return response()->json([
            'success' => true,
            'reservation' => [
                'id' => $reservation->reservation_id,
                'date' => $reservation->reserve_date,
                'time' => $reservation->reserve_time,
                'guests' => $reservation->guest_num,
                'status' => $reservation->status_id,
                'commitment_fee' => $reservation->commitment_fee,
                'fee_paid' => (bool) $reservation->fee_paid,
                'fee_transaction_id' => $reservation->fee_transaction_id,
                'payment_status' => $paymentStatus,
            ]
        ]);
    }
}
