<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use App\Models\TastyWallet;
use Igniter\Cart\Models\Order;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Loyalty points: customers earn points on completed orders and redeem them
 * as Tasty Wallet credit, which they then spend on food at checkout.
 *
 * Rates (set in the environment):
 *  LOYALTY_UGX_PER_POINT   UGX spent per point earned (default 1,000)
 *  LOYALTY_POINT_VALUE_UGX wallet credit per point redeemed (default UGX 10)
 *  LOYALTY_MIN_REDEEM      fewest points that can be redeemed (default 100)
 */
class LoyaltyService
{
    public static function ugxPerPoint(): int
    {
        return max(1, (int)env('LOYALTY_UGX_PER_POINT', 1000));
    }

    public static function pointValue(): int
    {
        return max(1, (int)env('LOYALTY_POINT_VALUE_UGX', 10));
    }

    public static function minRedeem(): int
    {
        return max(1, (int)env('LOYALTY_MIN_REDEEM', 100));
    }

    public function pointsFor(float $amount): int
    {
        return (int)floor($amount / static::ugxPerPoint());
    }

    /**
     * Award points for a completed order. Safe to call more than once: an
     * order earns at most once (unique order_id + type).
     */
    public function earnForOrder(Order $order): ?LoyaltyTransaction
    {
        $points = $this->pointsFor((float)$order->order_total);
        if (!$order->customer_id || $points < 1) {
            return null;
        }

        try {
            return DB::transaction(function () use ($order, $points) {
                $account = $this->lockedAccount((int)$order->customer_id);
                $account->points_balance += $points;
                $account->lifetime_points += $points;
                $account->save();

                return $account->transactions()->create([
                    'order_id' => $order->order_id,
                    'type' => LoyaltyTransaction::TYPE_EARN,
                    'points' => $points,
                    'balance_after' => $account->points_balance,
                    'description' => 'Earned on order UGA-'.str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                ]);
            });
        } catch (QueryException $e) {
            if ($this->isDuplicate($e)) {
                return null; // already earned
            }
            throw $e;
        }
    }

    /**
     * Take back the points of an order that was cancelled after completion.
     * Never takes the balance below zero (points may already be redeemed).
     */
    public function reverseForOrder(Order $order): ?LoyaltyTransaction
    {
        $earned = LoyaltyTransaction::where('order_id', $order->order_id)
            ->where('type', LoyaltyTransaction::TYPE_EARN)
            ->first();
        if (!$earned) {
            return null;
        }

        try {
            return DB::transaction(function () use ($order, $earned) {
                $account = $this->lockedAccount((int)$order->customer_id);
                $points = min($earned->points, $account->points_balance);
                $account->points_balance -= $points;
                $account->save();

                return $account->transactions()->create([
                    'order_id' => $order->order_id,
                    'type' => LoyaltyTransaction::TYPE_REVERSE,
                    'points' => -$points,
                    'balance_after' => $account->points_balance,
                    'description' => 'Reversed: order UGA-'.str_pad($order->order_id, 5, '0', STR_PAD_LEFT).' was cancelled',
                ]);
            });
        } catch (QueryException $e) {
            if ($this->isDuplicate($e)) {
                return null; // already reversed
            }
            throw $e;
        }
    }

    /**
     * Convert points into Tasty Wallet credit.
     *
     * @throws RuntimeException with a customer-facing message
     */
    public function redeem(int $customerId, int $points): LoyaltyTransaction
    {
        if ($points < static::minRedeem()) {
            throw new RuntimeException('You can redeem from '.number_format(static::minRedeem()).' points.');
        }

        return DB::transaction(function () use ($customerId, $points) {
            $account = $this->lockedAccount($customerId);
            if ($points > $account->points_balance) {
                throw new RuntimeException('You only have '.number_format($account->points_balance).' points.');
            }

            $credit = $points * static::pointValue();
            $wallet = TastyWallet::where('customer_id', $customerId)->lockForUpdate()->first()
                ?? TastyWallet::getOrCreateForCustomer($customerId);

            $balanceBefore = (float)$wallet->balance;
            $walletTransaction = $wallet->transactions()->create([
                'type' => 'bonus',
                'amount' => $credit,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore + $credit,
                'reference' => TastyWallet::generateReference('LOY'),
                'description' => 'Redeemed '.number_format($points).' loyalty points',
                'status' => 'completed',
            ]);
            $wallet->update(['balance' => $balanceBefore + $credit]);

            $account->points_balance -= $points;
            $account->save();

            return $account->transactions()->create([
                'wallet_transaction_id' => $walletTransaction->id,
                'type' => LoyaltyTransaction::TYPE_REDEEM,
                'points' => -$points,
                'balance_after' => $account->points_balance,
                'description' => 'Redeemed for UGX '.number_format($credit).' wallet credit',
            ]);
        });
    }

    public function summary(int $customerId, int $historyLimit = 15): array
    {
        $account = LoyaltyAccount::forCustomer($customerId);

        return [
            'points' => $account->points_balance,
            'lifetime_points' => $account->lifetime_points,
            'value_ugx' => $account->points_balance * static::pointValue(),
            'ugx_per_point' => static::ugxPerPoint(),
            'point_value_ugx' => static::pointValue(),
            'min_redeem' => static::minRedeem(),
            'history' => $account->transactions()->latest('id')->limit($historyLimit)->get()
                ->map(fn (LoyaltyTransaction $t) => [
                    'type' => $t->type,
                    'points' => $t->points,
                    'description' => $t->description,
                    'date' => $t->created_at?->format('j M Y'),
                ])->all(),
        ];
    }

    protected function lockedAccount(int $customerId): LoyaltyAccount
    {
        LoyaltyAccount::forCustomer($customerId);

        return LoyaltyAccount::where('customer_id', $customerId)->lockForUpdate()->firstOrFail();
    }

    protected function isDuplicate(QueryException $e): bool
    {
        return in_array($e->errorInfo[1] ?? null, [1062, 19], true) // MySQL / SQLite
            || str_contains($e->getMessage(), 'Duplicate entry');
    }
}
