<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'location_id',
        'scheduled_date',
        'scheduled_time',
        'is_recurring',
        'recurring_frequency',
        'next_occurrence',
        'cart_data',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'next_occurrence' => 'date',
        'is_recurring' => 'boolean',
        'cart_data' => 'array',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Recurring frequency constants
     */
    const FREQUENCY_NONE = null;
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_BIWEEKLY = 'biweekly';
    const FREQUENCY_MONTHLY = 'monthly';

    /**
     * Get the customer
     */
    public function customer()
    {
        return $this->belongsTo(\Igniter\User\Models\Customer::class, 'customer_id');
    }

    /**
     * Get the location
     */
    public function location()
    {
        return $this->belongsTo(\Igniter\Local\Models\Location::class, 'location_id');
    }

    /**
     * Get scheduled datetime
     */
    public function getScheduledDateTimeAttribute()
    {
        return $this->scheduled_date->setTimeFromTimeString($this->scheduled_time);
    }

    /**
     * Check if scheduled time is in the past
     */
    public function isScheduledTimePast(): bool
    {
        return $this->scheduled_date_time->isPast();
    }

    /**
     * Calculate next occurrence for recurring orders
     */
    public function calculateNextOccurrence(): ?string
    {
        if (!$this->is_recurring || !$this->recurring_frequency) {
            return null;
        }

        $current = $this->next_occurrence ?? $this->scheduled_date;
        
        switch ($this->recurring_frequency) {
            case self::FREQUENCY_DAILY:
                return $current->addDay()->format('Y-m-d');
            case self::FREQUENCY_WEEKLY:
                return $current->addWeek()->format('Y-m-d');
            case self::FREQUENCY_BIWEEKLY:
                return $current->addWeeks(2)->format('Y-m-d');
            case self::FREQUENCY_MONTHLY:
                return $current->addMonth()->format('Y-m-d');
            default:
                return null;
        }
    }

    /**
     * Update to next occurrence
     */
    public function moveToNextOccurrence(): void
    {
        if ($this->is_recurring) {
            $this->next_occurrence = $this->calculateNextOccurrence();
            $this->status = self::STATUS_PENDING;
            $this->save();
        }
    }

    /**
     * Get cart items count
     */
    public function getItemsCount(): int
    {
        if (!$this->cart_data || !isset($this->cart_data['items'])) {
            return 0;
        }
        return count($this->cart_data['items']);
    }

    /**
     * Get cart total
     */
    public function getCartTotal(): float
    {
        if (!$this->cart_data || !isset($this->cart_data['total'])) {
            return 0;
        }
        return (float) $this->cart_data['total'];
    }

    /**
     * Scope: Upcoming scheduled orders
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_date', '>=', now()->toDateString())
                     ->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    /**
     * Scope: By customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Recurring orders
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Scope: Due for processing
     */
    public function scopeDueForProcessing($query)
    {
        return $query->where('scheduled_date', now()->toDateString())
                     ->where('status', self::STATUS_CONFIRMED);
    }
}
