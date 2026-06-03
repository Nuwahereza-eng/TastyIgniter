<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'customer_subscriptions';

    protected $fillable = [
        'customer_id',
        'plan_id',
        'status',
        'started_at',
        'expires_at',
        'cancelled_at',
        'delivery_address',
        'preferred_delivery_time',
        'meals_remaining',
        'meals_used',
        'amount_paid',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'meals_remaining' => 'integer',
        'meals_used' => 'integer',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the customer
     */
    public function customer()
    {
        return $this->belongsTo(\Igniter\User\Models\Customer::class, 'customer_id');
    }

    /**
     * Get the subscription plan
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if subscription has meals remaining
     */
    public function hasMealsRemaining(): bool
    {
        return $this->meals_remaining > 0;
    }

    /**
     * Use a meal from subscription
     */
    public function useMeal(): bool
    {
        if (!$this->hasMealsRemaining()) {
            return false;
        }

        $this->decrement('meals_remaining');
        return true;
    }

    /**
     * Record that a delivered order consumed one meal from this subscription.
     *
     * Idempotent: a second call with the same order id is a no-op so the
     * status pipeline can safely fire `delivered` multiple times.
     */
    public function recordOrderDelivery(int $orderId): bool
    {
        if (!$orderId) {
            return false;
        }

        // Already recorded? short-circuit.
        if (SubscriptionMealUsage::where('subscription_id', $this->id)
            ->where('order_id', $orderId)
            ->exists()) {
            return false;
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($orderId) {
            SubscriptionMealUsage::create([
                'subscription_id' => $this->id,
                'order_id' => $orderId,
                'used_at' => now(),
            ]);

            if ($this->meals_remaining > 0) {
                $this->decrement('meals_remaining');
            }
            $this->increment('meals_used');

            return true;
        });
    }

    /**
     * All orders that have been served by this subscription, most recent first.
     */
    public function mealUsages()
    {
        return $this->hasMany(SubscriptionMealUsage::class, 'subscription_id')
            ->orderByDesc('used_at');
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration(): int
    {
        if (!$this->expires_at) {
            return 0;
        }
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get percentage of meals used
     */
    public function getMealsUsedPercentage(): float
    {
        if (!$this->plan || $this->plan->meals_per_period <= 0) {
            return 0;
        }
        
        $used = $this->plan->meals_per_period - $this->meals_remaining;
        return round(($used / $this->plan->meals_per_period) * 100, 1);
    }

    /**
     * Renew subscription
     */
    public function renew(): void
    {
        $plan = $this->plan;
        
        // Set new expiration based on billing period
        switch ($plan->billing_period) {
            case SubscriptionPlan::PERIOD_WEEKLY:
                $this->expires_at = now()->addWeek();
                break;
            case SubscriptionPlan::PERIOD_MONTHLY:
                $this->expires_at = now()->addMonth();
                break;
            case SubscriptionPlan::PERIOD_YEARLY:
                $this->expires_at = now()->addYear();
                break;
        }

        $this->meals_remaining = $plan->meals_per_period;
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    /**
     * Pause subscription
     */
    public function pause(): void
    {
        $this->status = self::STATUS_PAUSED;
        $this->save();
    }

    /**
     * Resume subscription
     */
    public function resume(): void
    {
        if ($this->status === self::STATUS_PAUSED && !$this->isExpired()) {
            $this->status = self::STATUS_ACTIVE;
            $this->save();
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(): void
    {
        $this->status = self::STATUS_CANCELLED;
        $this->auto_renew = false;
        $this->save();
    }

    /**
     * Scope: Active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope: By customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Expiring soon (within 7 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->whereBetween('expires_at', [now(), now()->addDays(7)]);
    }

    /**
     * Scope: Due for renewal
     */
    public function scopeDueForRenewal($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('auto_renew', true)
                     ->where('expires_at', '<=', now());
    }
}
