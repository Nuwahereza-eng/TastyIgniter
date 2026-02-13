<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $table = 'tasty_wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference',
        'description',
        'payment_method',
        'external_reference',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(TastyWallet::class, 'wallet_id');
    }

    public function getFormattedAmount(): string
    {
        $prefix = in_array($this->type, ['deposit', 'refund', 'cashback', 'bonus']) ? '+' : '-';
        return $prefix . ' ' . currency_format($this->amount);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'payment' => 'Order Payment',
            'refund' => 'Refund',
            'cashback' => 'Cashback',
            'bonus' => 'Bonus',
            default => ucfirst($this->type),
        };
    }

    public function getTypeIcon(): string
    {
        return match($this->type) {
            'deposit' => 'fa-arrow-down text-success',
            'withdrawal' => 'fa-arrow-up text-danger',
            'payment' => 'fa-shopping-cart text-warning',
            'refund' => 'fa-undo text-info',
            'cashback' => 'fa-gift text-success',
            'bonus' => 'fa-star text-warning',
            default => 'fa-exchange-alt',
        };
    }

    public function isCredit(): bool
    {
        return in_array($this->type, ['deposit', 'refund', 'cashback', 'bonus']);
    }

    public function isDebit(): bool
    {
        return in_array($this->type, ['withdrawal', 'payment']);
    }
}
