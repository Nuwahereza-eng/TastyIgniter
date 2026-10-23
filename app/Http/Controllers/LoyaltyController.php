<?php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use Igniter\User\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class LoyaltyController extends Controller
{
    public function __construct(protected LoyaltyService $loyalty) {}

    public function summary(): JsonResponse
    {
        $customer = Auth::customer();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Please log in'], 401);
        }

        return response()->json(['success' => true] + $this->loyalty->summary((int)$customer->getKey()));
    }

    public function redeem(Request $request): JsonResponse
    {
        $customer = Auth::customer();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Please log in'], 401);
        }

        $request->validate(['points' => 'required|integer|min:1']);

        try {
            $this->loyalty->redeem((int)$customer->getKey(), (int)$request->points);
        } catch (RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $wallet = \App\Models\TastyWallet::where('customer_id', $customer->getKey())->first();

        return response()->json([
            'success' => true,
            'message' => 'Added UGX '.number_format((int)$request->points * LoyaltyService::pointValue()).' to your Tasty Wallet.',
            'wallet_balance' => (float)($wallet->balance ?? 0),
        ] + $this->loyalty->summary((int)$customer->getKey()));
    }
}
