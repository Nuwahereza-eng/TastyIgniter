<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;

    protected $table = 'order_tracking';

    protected $fillable = [
        'order_id',
        'status',
        'rider_name',
        'rider_phone',
        'rider_photo',
        'current_lat',
        'current_lng',
        'eta_minutes',
    ];

    protected $casts = [
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
        'eta_minutes' => 'integer',
    ];

    /**
     * Status constants (matching frontend)
     */
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_ON_THE_WAY = 'on_the_way';
    const STATUS_NEARBY = 'nearby';
    const STATUS_DELIVERED = 'delivered';

    /**
     * Status display names
     */
    const STATUS_LABELS = [
        self::STATUS_CONFIRMED => 'Order Confirmed',
        self::STATUS_PREPARING => 'Preparing Your Food',
        self::STATUS_READY => 'Ready for Pickup',
        self::STATUS_PICKED_UP => 'Picked Up by Rider',
        self::STATUS_ON_THE_WAY => 'On the Way',
        self::STATUS_NEARBY => 'Almost There',
        self::STATUS_DELIVERED => 'Delivered',
    ];

    /**
     * Status descriptions
     */
    const STATUS_DESCRIPTIONS = [
        self::STATUS_CONFIRMED => 'Your order has been received and confirmed',
        self::STATUS_PREPARING => 'The restaurant is preparing your delicious meal',
        self::STATUS_READY => 'Your food is ready and waiting for the rider',
        self::STATUS_PICKED_UP => 'Your rider has picked up your order',
        self::STATUS_ON_THE_WAY => 'Your order is on the way to you',
        self::STATUS_NEARBY => 'Your rider is almost at your location',
        self::STATUS_DELIVERED => 'Your order has been delivered. Enjoy!',
    ];

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(\Igniter\Cart\Models\Order::class, 'order_id');
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get status description
     */
    public function getStatusDescription(): string
    {
        return self::STATUS_DESCRIPTIONS[$this->status] ?? '';
    }

    /**
     * Get status progress percentage
     */
    public function getProgressPercentage(): int
    {
        $statuses = array_keys(self::STATUS_LABELS);
        $currentIndex = array_search($this->status, $statuses);
        
        if ($currentIndex === false) {
            return 0;
        }

        return (int) round((($currentIndex + 1) / count($statuses)) * 100);
    }

    /**
     * Check if order has rider assigned
     */
    public function hasRider(): bool
    {
        return !empty($this->rider_name);
    }

    /**
     * Check if order is in transit
     */
    public function isInTransit(): bool
    {
        return in_array($this->status, [
            self::STATUS_PICKED_UP,
            self::STATUS_ON_THE_WAY,
            self::STATUS_NEARBY,
        ]);
    }

    /**
     * Check if order is delivered
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Update rider location
     */
    public function updateLocation(float $lat, float $lng, int $eta = null): void
    {
        $this->current_lat = $lat;
        $this->current_lng = $lng;
        
        if ($eta !== null) {
            $this->eta_minutes = $eta;
        }
        
        $this->save();
    }

    /**
     * Advance to next status
     */
    public function advanceStatus(): void
    {
        $statuses = array_keys(self::STATUS_LABELS);
        $currentIndex = array_search($this->status, $statuses);
        
        if ($currentIndex !== false && $currentIndex < count($statuses) - 1) {
            $this->status = $statuses[$currentIndex + 1];
            $this->save();
        }
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return self::STATUS_LABELS;
    }

    /**
     * Create tracking for an order
     */
    public static function createForOrder(int $orderId): self
    {
        return self::create([
            'order_id' => $orderId,
            'status' => self::STATUS_CONFIRMED,
        ]);
    }

    /**
     * Scope: By order
     */
    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Scope: Active (not delivered)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_DELIVERED);
    }

    /**
     * Scope: In transit
     */
    public function scopeInTransit($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PICKED_UP,
            self::STATUS_ON_THE_WAY,
            self::STATUS_NEARBY,
        ]);
    }
}
