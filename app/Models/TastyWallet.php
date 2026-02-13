<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Igniter\User\Models\Customer;
use Illuminate\Support\Str;

class TastyWallet extends Model
{
    protected $table = 'tasty_wallets';

    protected $fillable = [
        'customer_id',
        'balance',
        'is_active',
        'pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'pin',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    public static function getOrCreateForCustomer(int $customerId): self
    {
        return self::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0, 'is_active' => true]
        );
    }

    public static function generateReference(string $prefix = 'TW'): string
    {
        return $prefix . strtoupper(Str::random(12)) . time();
    }

    public function getFormattedBalance(): string
    {
        return currency_format($this->balance);
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function deposit(float $amount, string $paymentMethod, ?string $externalRef = null, ?string $description = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore + $amount;

        $transaction = $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference' => self::generateReference('DEP'),
            'description' => $description ?? 'Wallet deposit via ' . $paymentMethod,
            'payment_method' => $paymentMethod,
            'external_reference' => $externalRef,
            'status' => 'completed',
        ]);

        $this->update(['balance' => $balanceAfter]);
        return $transaction;
    }

    public function withdraw(float $amount, string $paymentMethod, ?string $phoneNumber = null, ?string $description = null): WalletTransaction
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('Insufficient wallet balance');
        }

        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore - $amount;

        $transaction = $this->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference' => self::generateReference('WTH'),
            'description' => $description ?? 'Withdrawal to ' . $paymentMethod,
            'payment_method' => $paymentMethod,
            'external_reference' => $phoneNumber,
            'status' => 'completed',
        ]);

        $this->update(['balance' => $balanceAfter]);
        return $transaction;
    }

    public function pay(float $amount, ?string $orderId = null, ?string $description = null): WalletTransaction
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('Insufficient wallet balance');
        }

        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore - $amount;

        $transaction = $this->transactions()->create([
            'type' => 'payment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference' => self::generateReference('PAY'),
            'description' => $description ?? 'Payment for order #' . $orderId,
            'external_reference' => $orderId,
            'status' => 'completed',
        ]);

        $this->update(['balance' => $balanceAfter]);
        return $transaction;
    }

    public function addCashback(float $amount, ?string $orderId = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore + $amount;

        $transaction = $this->transactions()->create([
            'type' => 'cashback',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference' => self::generateReference('CSH'),
            'description' => 'Cashback reward' . ($orderId ? ' for order #' . $orderId : ''),
            'external_reference' => $orderId,
            'status' => 'completed',
        ]);

        $this->update(['balance' => $balanceAfter]);
        return $transaction;
    }

    public function getRecentTransactions(int $limit = 10)
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
