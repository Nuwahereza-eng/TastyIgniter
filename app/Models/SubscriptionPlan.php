<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_period',
        'meals_per_period',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'meals_per_period' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Billing period constants
     */
    const PERIOD_WEEKLY = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_YEARLY = 'yearly';

    /**
     * Get all subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    /**
     * Get active subscriptions count
     */
    public function getActiveSubscribersCount(): int
    {
        return $this->subscriptions()->where('status', Subscription::STATUS_ACTIVE)->count();
    }

    /**
     * Get price per meal
     */
    public function getPricePerMeal(): float
    {
        if ($this->meals_per_period <= 0) {
            return 0;
        }
        return round($this->price / $this->meals_per_period, 2);
    }

    /**
     * Format price with currency
     */
    public function getFormattedPrice(): string
    {
        return 'UGX ' . number_format($this->price, 0);
    }

    /**
     * Get billing period label
     */
    public function getBillingPeriodLabel(): string
    {
        switch ($this->billing_period) {
            case self::PERIOD_WEEKLY:
                return 'per week';
            case self::PERIOD_MONTHLY:
                return 'per month';
            case self::PERIOD_YEARLY:
                return 'per year';
            default:
                return $this->billing_period;
        }
    }

    /**
     * Scope: Active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by price
     */
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Check if plan has feature
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    /**
     * Get default plans for seeding
     */
    public static function getDefaultPlans(): array
    {
        return [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'price' => 150000,
                'billing_period' => self::PERIOD_MONTHLY,
                'meals_per_period' => 12,
                'features' => [
                    '12 meals per month',
                    'Free delivery',
                    '5% discount on extras',
                    'Email support',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 280000,
                'billing_period' => self::PERIOD_MONTHLY,
                'meals_per_period' => 20,
                'features' => [
                    '20 meals per month',
                    'Priority delivery',
                    '10% discount on all orders',
                    'Priority support',
                    'Exclusive menu access',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Family',
                'slug' => 'family',
                'price' => 450000,
                'billing_period' => self::PERIOD_MONTHLY,
                'meals_per_period' => 40,
                'features' => [
                    '40 meals per month',
                    'Free priority delivery',
                    '15% discount on all orders',
                    '24/7 VIP support',
                    'Exclusive menu access',
                    'Family sharing (up to 4 members)',
                ],
                'is_active' => true,
            ],
        ];
    }
}
