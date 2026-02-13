<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payment_transactions';
    
    protected $fillable = [
        'customer_id',
        'tx_ref',
        'flw_ref',
        'amount',
        'currency',
        'payment_method',
        'payment_type',
        'reference_id',
        'status',
        'provider',
        'metadata',
        'verified_at',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'verified_at' => 'datetime',
    ];
    
    // Payment statuses
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    
    // Payment types
    const TYPE_ORDER = 'order';
    const TYPE_SUBSCRIPTION = 'subscription';
    
    // Payment methods
    const METHOD_MTN = 'mtn';
    const METHOD_AIRTEL = 'airtel';
    const METHOD_CARD = 'card';
    
    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount);
    }
    
    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESSFUL;
    }
    
    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Get payment method display name
     */
    public function getMethodNameAttribute(): string
    {
        return match($this->payment_method) {
            self::METHOD_MTN => 'MTN Mobile Money',
            self::METHOD_AIRTEL => 'Airtel Money',
            self::METHOD_CARD => 'Card',
            default => ucfirst($this->payment_method),
        };
    }
    
    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_SUCCESSFUL => 'bg-success',
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_REFUNDED => 'bg-info',
            default => 'bg-secondary',
        };
    }
    
    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESSFUL);
    }
    
    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope for a specific type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('payment_type', $type);
    }
    
    /**
     * Get the customer (if using standard Auth)
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\User::class, 'customer_id');
    }
    
    /**
     * Get the related subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'reference_id');
    }
}
