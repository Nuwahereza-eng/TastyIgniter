<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Audit row recording every delivery that consumed a meal from a customer's
 * subscription. Used both for idempotency (so we never double-decrement on
 * status re-sends) and for showing the customer/admin which orders were
 * served by the active subscription.
 */
class SubscriptionMealUsage extends Model
{
    protected $table = 'subscription_meals';

    protected $fillable = [
        'subscription_id',
        'order_id',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
