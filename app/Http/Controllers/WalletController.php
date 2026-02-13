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
     * Simple deposit (simulated for test mode)
     */
    public function deposit(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Please log in to deposit'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:5000000',
            'payment_method' => 'required|in:mobilemoney,flutterwave',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $wallet = TastyWallet::getOrCreateForCustomer($customerId);
            $amount = floatval($request->input('amount'));
            $paymentMethod = $request->input('payment_method');
            $provider = $request->input('provider', 'mtn');

            // In test mode, simulate successful payment immediately
            $description = $paymentMethod === 'mobilemoney' 
                ? 'Deposit via ' . strtoupper($provider) . ' Mobile Money'
                : 'Deposit via Card';

            $transaction = $wallet->deposit(
                $amount,
                $paymentMethod,
                'TEST_' . TastyWallet::generateReference('DEP'),
                $description
            );

            return response()->json([
                'success' => true,
                'message' => 'Deposit of UGX ' . number_format($amount) . ' successful!',
                'transaction' => [
                    'reference' => $transaction->reference,
                    'amount' => $transaction->amount,
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => 'UGX ' . number_format($wallet->fresh()->balance, 0),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Wallet deposit error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Withdraw from wallet
     */
    public function withdraw(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return response()->json(['error' => 'Please log in to withdraw'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000',
            'phone_number' => 'required|string',
            'provider' => 'required|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $wallet = TastyWallet::getOrCreateForCustomer($customerId);
            $amount = floatval($request->input('amount'));

            if (!$wallet->hasSufficientBalance($amount)) {
                return response()->json(['error' => 'Insufficient wallet balance'], 422);
            }

            $provider = $request->input('provider');
            $phone = '+256' . $request->input('phone_number');

            $transaction = $wallet->withdraw(
                $amount,
                $provider . '_mobilemoney',
                $phone,
                'Withdrawal to ' . $phone . ' via ' . strtoupper($provider)
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal of UGX ' . number_format($amount) . ' to ' . $phone . ' successful!',
                'transaction' => [
                    'reference' => $transaction->reference,
                    'amount' => $transaction->amount,
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => 'UGX ' . number_format($wallet->fresh()->balance, 0),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Wallet withdraw error: ' . $e->getMessage());
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
