<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupOrderParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_order_id',
        'customer_id',
        'guest_name',
        'guest_email',
        'is_host',
        'subtotal',
        'share_amount',
        'has_paid',
        'payment_tx_ref',
        'paid_at',
        'cart_items',
    ];

    protected $casts = [
        'cart_items' => 'array',
        'subtotal' => 'decimal:2',
        'share_amount' => 'decimal:2',
        'is_host' => 'boolean',
        'has_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    /**
     * Append accessors to JSON for backward compatibility
     */
    protected $appends = ['name', 'status', 'items'];

    /**
     * Status constants (for compatibility - using has_paid instead)
     */
    const STATUS_JOINED = 'joined';
    const STATUS_SELECTING = 'selecting';
    const STATUS_READY = 'ready';
    const STATUS_PAID = 'paid';

    /**
     * Get the group order
     */
    public function groupOrder()
    {
        return $this->belongsTo(GroupOrder::class);
    }

    /**
     * Get the customer (if registered)
     */
    public function customer()
    {
        return $this->belongsTo(\Igniter\User\Models\Customer::class, 'customer_id');
    }

    /**
     * Check if participant has finished selecting items
     */
    public function hasFinishedSelecting(): bool
    {
        return !empty($this->cart_items) && count($this->cart_items) > 0;
    }

    /**
     * Get display name
     */
    public function getDisplayName(): string
    {
        if ($this->customer) {
            return $this->customer->full_name;
        }
        return $this->guest_name ?? 'Guest';
    }

    /**
     * Alias for name attribute (for backward compatibility)
     */
    public function getNameAttribute()
    {
        return $this->getDisplayName();
    }

    /**
     * Alias for items attribute (for backward compatibility)
     */
    public function getItemsAttribute()
    {
        return $this->cart_items;
    }

    /**
     * Alias for status attribute (for backward compatibility)
     */
    public function getStatusAttribute()
    {
        if ($this->has_paid) {
            return self::STATUS_PAID;
        }
        if (!empty($this->cart_items) && count($this->cart_items) > 0) {
            return self::STATUS_READY;
        }
        return self::STATUS_SELECTING;
    }

    /**
     * Add item to participant's order
     */
    public function addItem(array $item): void
    {
        $items = $this->cart_items ?? [];
        $items[] = $item;
        $this->cart_items = $items;
        $this->calculateSubtotal();
    }

    /**
     * Calculate subtotal from items
     */
    public function calculateSubtotal(): void
    {
        $subtotal = 0;
        foreach ($this->cart_items ?? [] as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $this->subtotal = $subtotal;
    }

    /**
     * Scope: By group order
     */
    public function scopeForGroupOrder($query, $groupOrderId)
    {
        return $query->where('group_order_id', $groupOrderId);
    }

    /**
     * Scope: Ready participants
     */
    public function scopeReady($query)
    {
        return $query->whereNotNull('cart_items')->whereRaw('JSON_LENGTH(cart_items) > 0');
    }
}
