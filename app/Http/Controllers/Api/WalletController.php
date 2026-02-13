<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TastyWallet;
use App\Models\WalletTransaction;
use Igniter\User\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Get wallet details for authenticated customer
     */
    public function getWallet(Request $request): JsonResponse
    {
        $customer = Auth::customer();
        
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);

        return response()->json([
            'success' => true,
            'wallet' => [
                'id' => $wallet->id,
                'balance' => $wallet->balance,
                'formatted_balance' => $wallet->getFormattedBalance(),
                'is_active' => $wallet->is_active,
            ],
        ]);
    }

    /**
     * Get wallet transactions
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $customer = Auth::customer();
        
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'transactions' => $transactions->map(function ($tx) {
                return [
                    'id' => $tx->id,
                    'type' => $tx->type,
                    'type_label' => $tx->getTypeLabel(),
                    'type_icon' => $tx->getTypeIcon(),
                    'amount' => $tx->amount,
                    'formatted_amount' => $tx->getFormattedAmount(),
                    'balance_after' => $tx->balance_after,
                    'reference' => $tx->reference,
                    'description' => $tx->description,
                    'status' => $tx->status,
                    'is_credit' => $tx->isCredit(),
                    'created_at' => $tx->created_at->format('M d, Y H:i'),
                ];
            }),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    /**
     * Deposit money into wallet
     */
    public function deposit(Request $request): JsonResponse
    {
        $customer = Auth::customer();
        
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:5000000',
            'payment_method' => 'required|in:mobilemoney,flutterwave',
            'phone_number' => 'required_if:payment_method,mobilemoney|string',
            'provider' => 'required_if:payment_method,mobilemoney|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
            $amount = floatval($request->amount);
            $paymentMethod = $request->payment_method;

            // In test mode, simulate successful payment
            $externalRef = 'TEST_' . TastyWallet::generateReference('DEP');

            $transaction = $wallet->deposit(
                $amount,
                $paymentMethod,
                $externalRef,
                'Deposit via ' . ucfirst($paymentMethod)
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit successful!',
                'transaction' => [
                    'reference' => $transaction->reference,
                    'amount' => $transaction->amount,
                    'formatted_amount' => $transaction->getFormattedAmount(),
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(Request $request): JsonResponse
    {
        $customer = Auth::customer();
        
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:1000000',
            'payment_method' => 'required|in:mobilemoney',
            'phone_number' => 'required|string|regex:/^[0-9]{9}$/',
            'provider' => 'required|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
            $amount = floatval($request->amount);

            if (!$wallet->hasSufficientBalance($amount)) {
                return response()->json(['error' => 'Insufficient wallet balance'], 422);
            }

            $paymentMethod = $request->provider . '_mobilemoney';
            $phoneNumber = '+256' . $request->phone_number;

            // In test mode, simulate successful withdrawal
            $transaction = $wallet->withdraw(
                $amount,
                $paymentMethod,
                $phoneNumber,
                'Withdrawal to ' . $phoneNumber
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal successful! Funds sent to ' . $phoneNumber,
                'transaction' => [
                    'reference' => $transaction->reference,
                    'amount' => $transaction->amount,
                    'formatted_amount' => $transaction->getFormattedAmount(),
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
