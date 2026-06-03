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
     * Deposit money into wallet via MarzPay (or simulated when Marz is
     * not configured / in demo mode). Reuses PaymentController::initialize
     * so collect-money + webhook completion are shared with order flows.
     */
    public function deposit(Request $request): JsonResponse
    {
        $customer = Auth::customer();

        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1000|max:5000000',
            'payment_method' => 'required|in:mtn,airtel,card,mobilemoney,flutterwave',
            'phone_number' => 'nullable|string',
            'phone' => 'nullable|string',
            'provider' => 'nullable|in:mtn,airtel',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
            $amount = floatval($request->amount);

            $method = $request->payment_method;
            if ($method === 'mobilemoney') {
                $method = $request->input('provider', 'mtn');
            } elseif ($method === 'flutterwave') {
                $method = 'card';
            }
            $phone = $request->input('phone') ?? $request->input('phone_number');

            $pending = $wallet->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'reference' => TastyWallet::generateReference('DEP-PEND'),
                'description' => in_array($method, ['mtn', 'airtel'], true)
                    ? 'Wallet top-up via ' . strtoupper($method) . ' Mobile Money'
                    : 'Wallet top-up via Card',
                'payment_method' => $method,
                'status' => 'pending',
            ]);

            $sub = Request::create('/payment/initialize', 'POST', [
                'amount' => $amount,
                'email' => $customer->email,
                'phone' => $phone,
                'name' => trim($customer->full_name ?? $customer->first_name ?? 'Customer'),
                'type' => \App\Models\Payment::TYPE_WALLET_DEPOSIT,
                'payment_method' => $method,
            ]);

            $jsonResponse = app(\App\Http\Controllers\PaymentController::class)->initialize($sub);
            $body = json_decode($jsonResponse->getContent(), true) ?: [];

            if (empty($body['success'])) {
                $pending->update(['status' => 'failed']);
                return response()->json(['error' => $body['message'] ?? 'Top-up initialization failed'], 400);
            }

            $pending->update(['reference' => $body['tx_ref']]);
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Withdraw money from wallet via MarzPay payout (or queued for manual
     * fulfilment when Marz disbursement is not configured).
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

        $wallet = TastyWallet::getOrCreateForCustomer($customer->customer_id);
        $amount = floatval($request->amount);

        if (!$wallet->hasSufficientBalance($amount)) {
            return response()->json(['error' => 'Insufficient wallet balance'], 422);
        }

        $phone = '+256' . $request->phone_number;
        $provider = $request->provider;
        $reference = TastyWallet::generateReference('WTH');

        try {
            $debit = $wallet->withdraw(
                $amount,
                $provider . '_mobilemoney',
                $phone,
                'Withdrawal to ' . $phone
            );
            $debit->update(['status' => 'pending', 'reference' => $reference]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $service = app(\App\Services\PaymentService::class);
        $payout = $service->sendMarzPayout([
            'amount' => $amount,
            'phone' => $phone,
            'reference' => $reference,
            'description' => 'UgaEats wallet withdrawal to ' . strtoupper($provider),
        ]);

        if (empty($payout['configured'])) {
            $debit->update([
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
                    'formatted_amount' => $debit->getFormattedAmount(),
                ],
                'wallet' => [
                    'balance' => $wallet->fresh()->balance,
                    'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
                ],
            ]);
        }

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
                'formatted_amount' => $debit->getFormattedAmount(),
            ],
            'wallet' => [
                'balance' => $wallet->fresh()->balance,
                'formatted_balance' => $wallet->fresh()->getFormattedBalance(),
            ],
        ]);
    }
}
