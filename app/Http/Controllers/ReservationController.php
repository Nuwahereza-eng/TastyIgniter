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
     * Default commitment fee in UGX
     */
    const COMMITMENT_FEE = 10000;

    /**
     * Create a reservation with commitment fee
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'location_id' => 'required|integer',
                'date' => 'required|date',
                'time' => 'required',
                'guest' => 'required|integer|min:1|max:20',
                'first_name' => 'required|string|max:255',
                'email' => 'required|email',
                'telephone' => 'required|string',
                'comment' => 'nullable|string',
                'payment_method' => 'required|in:wallet,mtn,airtel',
            ]);

            $customer = \Igniter\User\Facades\Auth::customer();
            $customerId = $customer ? $customer->customer_id : null;

            // Check commitment fee
            $commitmentFee = self::COMMITMENT_FEE;

            // If paying with wallet, verify balance
            if ($validated['payment_method'] === 'wallet') {
                if (!$customerId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please login to pay with wallet'
                    ], 401);
                }

                $wallet = TastyWallet::getOrCreateForCustomer($customerId);
                
                if (!$wallet->hasSufficientBalance($commitmentFee)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient wallet balance. You need UGX ' . number_format($commitmentFee) . ' but have UGX ' . number_format($wallet->balance)
                    ], 400);
                }
            }

            // Create the reservation
            $reservationData = [
                'location_id' => $validated['location_id'],
                'table_id' => 0, // Will be assigned by admin
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

            // Process payment
            $paymentSuccess = false;
            $transactionId = null;

            if ($validated['payment_method'] === 'wallet') {
                // Deduct from wallet
                $wallet = TastyWallet::getOrCreateForCustomer($customerId);
                $transaction = $wallet->pay(
                    $commitmentFee,
                    "Reservation #{$reservationId} commitment fee"
                );
                
                if ($transaction) {
                    $paymentSuccess = true;
                    $transactionId = 'WAL-' . $transaction->id;
                }
            } else {
                // Mobile money payment - simulate for now
                // In production, integrate with actual payment gateway
                $paymentSuccess = true;
                $transactionId = strtoupper($validated['payment_method']) . '-' . Str::random(10);
            }

            if ($paymentSuccess) {
                // Update reservation with payment info
                DB::table('reservations')
                    ->where('reservation_id', $reservationId)
                    ->update([
                        'fee_paid' => 1,
                        'fee_transaction_id' => $transactionId,
                        'fee_paid_at' => now(),
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Reservation confirmed! Your commitment fee has been paid.',
                    'reservation' => [
                        'id' => $reservationId,
                        'date' => $validated['date'],
                        'time' => $validated['time'],
                        'guests' => $validated['guest'],
                        'commitment_fee' => $commitmentFee,
                        'transaction_id' => $transactionId,
                    ]
                ]);
            } else {
                // Payment failed - delete reservation
                DB::table('reservations')->where('reservation_id', $reservationId)->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed. Please try again.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Reservation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get commitment fee amount
     */
    public function getFee(Request $request): JsonResponse
    {
        $customer = \Igniter\User\Facades\Auth::customer();
        $walletBalance = 0;

        if ($customer) {
            $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
            $walletBalance = $wallet->balance;
        }

        return response()->json([
            'success' => true,
            'commitment_fee' => self::COMMITMENT_FEE,
            'formatted_fee' => 'UGX ' . number_format(self::COMMITMENT_FEE),
            'wallet_balance' => $walletBalance,
            'formatted_wallet_balance' => 'UGX ' . number_format($walletBalance),
            'can_pay_with_wallet' => $walletBalance >= self::COMMITMENT_FEE,
            'is_logged_in' => $customer !== null,
        ]);
    }

    /**
     * Check reservation status
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
            ]
        ]);
    }
}
