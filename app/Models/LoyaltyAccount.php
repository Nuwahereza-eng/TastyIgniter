<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyAccount extends Model
{
    protected $table = 'loyalty_accounts';

    protected $fillable = ['customer_id', 'points_balance', 'lifetime_points'];

    protected $casts = [
        'points_balance' => 'integer',
        'lifetime_points' => 'integer',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public static function forCustomer(int $customerId): self
    {
        return static::firstOrCreate(['customer_id' => $customerId], ['points_balance' => 0, 'lifetime_points' => 0]);
    }
}
