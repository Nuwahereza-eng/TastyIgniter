<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GroupOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'host_customer_id',
        'code',
        'name',
        'location_id',
        'deadline',
        'split_method',
        'status',
        'total_amount',
        'order_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Append accessors to JSON for backward compatibility
     */
    protected $appends = ['title', 'invite_code', 'deadline_at'];

    /**
     * Status constants (matching DB enum)
     */
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'closed';
    const STATUS_ORDERED = 'ordered';
    const STATUS_CANCELLED = 'cancelled';

    // Aliases for backward compatibility
    const STATUS_PENDING = 'open';
    const STATUS_COLLECTING = 'open';
    const STATUS_ORDERING = 'closed';
    const STATUS_COMPLETED = 'ordered';

    /**
     * Split method constants (matching DB enum)
     */
    const SPLIT_EQUAL = 'equal';
    const SPLIT_BY_ITEM = 'individual';
    const SPLIT_HOST_PAYS = 'host';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = self::generateCode();
            }
            if (empty($model->status)) {
                $model->status = self::STATUS_OPEN;
            }
        });
    }

    /**
     * Generate unique invite code
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Get the host customer
     */
    public function host()
    {
        return $this->belongsTo(\Igniter\User\Models\Customer::class, 'host_customer_id');
    }

    /**
     * Get all participants
     */
    public function participants()
    {
        return $this->hasMany(GroupOrderParticipant::class);
    }

    /**
     * Get the location
     */
    public function location()
    {
        return $this->belongsTo(\Igniter\Local\Models\Location::class, 'location_id');
    }

    /**
     * Check if deadline has passed
     */
    public function isDeadlinePassed(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    /**
     * Check if group order is still accepting participants
     */
    public function isAcceptingParticipants(): bool
    {
        return $this->status === self::STATUS_OPEN && !$this->isDeadlinePassed();
    }

    /**
     * Calculate total from all participants
     */
    public function calculateTotal(): float
    {
        return $this->participants()->sum('subtotal');
    }

    /**
     * Compute each participant's share according to the split method.
     * Returns an array keyed by participant id: [participantId => share_amount].
     */
    public function computeShares(): array
    {
        $participants = $this->participants()->get();
        $total = (float) $this->calculateTotal();
        $shares = [];

        switch ($this->split_method) {
            case self::SPLIT_HOST_PAYS:
                foreach ($participants as $p) {
                    $shares[$p->id] = $p->is_host ? round($total, 2) : 0.0;
                }
                break;

            case self::SPLIT_EQUAL:
                $count = max(1, $participants->count());
                $per = round($total / $count, 2);
                foreach ($participants as $p) {
                    $shares[$p->id] = $per;
                }
                break;

            case self::SPLIT_BY_ITEM:
            default:
                foreach ($participants as $p) {
                    $shares[$p->id] = round((float) $p->subtotal, 2);
                }
                break;
        }

        return $shares;
    }

    /**
     * Persist the computed shares to each participant row.
     */
    public function applyShares(): void
    {
        $shares = $this->computeShares();
        foreach ($shares as $participantId => $amount) {
            GroupOrderParticipant::where('id', $participantId)
                ->update(['share_amount' => $amount]);
        }
        $this->total_amount = $this->calculateTotal();
        $this->save();
    }

    /**
     * True when every participant with a non-zero share has paid.
     */
    public function isFullyPaid(): bool
    {
        $remaining = $this->participants()
            ->where('share_amount', '>', 0)
            ->where('has_paid', false)
            ->count();
        return $remaining === 0;
    }

    /**
     * Get share link
     */
    public function getShareLink(): string
    {
        return url('/group-order/join/' . $this->code);
    }

    /**
     * Getters for backward compatibility (aliases)
     */
    public function getInviteCodeAttribute()
    {
        return $this->code;
    }

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getDeadlineAtAttribute()
    {
        return $this->deadline;
    }

    /**
     * Scope: Active group orders
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope: By host
     */
    public function scopeByHost($query, $customerId)
    {
        return $query->where('host_customer_id', $customerId);
    }
}
