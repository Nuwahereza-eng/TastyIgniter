<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Thin Eloquent model over the existing `admin_users` table so the tracking
 * subsystem can resolve assignable delivery riders without reaching across
 * vendor namespaces. Only the columns used by the rider dashboard and the
 * OrderTrackingController are exposed.
 */
class Rider extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'is_available',
        'current_order_id',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'current_order_id' => 'integer',
    ];

    public function scopeDeliveryStaff($query)
    {
        return $query
            ->join('admin_user_roles as r', 'admin_users.user_role_id', '=', 'r.user_role_id')
            ->where('r.code', 'delivery')
            ->where('admin_users.status', 1)
            ->select([
                'admin_users.user_id',
                'admin_users.name',
                'admin_users.email',
                'admin_users.telephone',
                'admin_users.is_available',
                'admin_users.current_order_id',
            ]);
    }

    public function scopeAvailable($query)
    {
        return $query->where('admin_users.is_available', 1)
            ->whereNull('admin_users.current_order_id');
    }
}
