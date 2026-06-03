<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aligns the order_tracking + admin_users tables with the live OrderTracking
 * model and the standalone rider-dashboard.php so the end-to-end track-order
 * flow (place order → auto-create tracking → admin/rider updates → live polling)
 * works without column-name errors.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('order_tracking')) {
            Schema::table('order_tracking', function (Blueprint $table) {
                if (!Schema::hasColumn('order_tracking', 'rider_name')) {
                    $table->string('rider_name')->nullable()->after('order_id');
                }
                if (!Schema::hasColumn('order_tracking', 'rider_phone')) {
                    $table->string('rider_phone', 32)->nullable()->after('rider_name');
                }
                if (!Schema::hasColumn('order_tracking', 'rider_photo')) {
                    $table->string('rider_photo')->nullable()->after('rider_phone');
                }
                if (!Schema::hasColumn('order_tracking', 'current_lat')) {
                    $table->decimal('current_lat', 10, 8)->nullable()->after('rider_photo');
                }
                if (!Schema::hasColumn('order_tracking', 'current_lng')) {
                    $table->decimal('current_lng', 11, 8)->nullable()->after('current_lat');
                }
                if (!Schema::hasColumn('order_tracking', 'assigned_rider_id')) {
                    $table->unsignedBigInteger('assigned_rider_id')->nullable()->after('current_lng');
                    $table->index('assigned_rider_id');
                }
                if (!Schema::hasColumn('order_tracking', 'rider_accepted_at')) {
                    $table->dateTime('rider_accepted_at')->nullable()->after('assigned_rider_id');
                }
                if (!Schema::hasColumn('order_tracking', 'rider_rejected_at')) {
                    $table->dateTime('rider_rejected_at')->nullable()->after('rider_accepted_at');
                }
                if (!Schema::hasColumn('order_tracking', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }

        if (Schema::hasTable('admin_users')) {
            Schema::table('admin_users', function (Blueprint $table) {
                if (!Schema::hasColumn('admin_users', 'is_available')) {
                    $table->boolean('is_available')->default(true);
                }
                if (!Schema::hasColumn('admin_users', 'current_order_id')) {
                    $table->unsignedBigInteger('current_order_id')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_tracking')) {
            Schema::table('order_tracking', function (Blueprint $table) {
                foreach ([
                    'rider_name', 'rider_phone', 'rider_photo',
                    'current_lat', 'current_lng',
                    'assigned_rider_id', 'rider_accepted_at', 'rider_rejected_at',
                    'notes',
                ] as $col) {
                    if (Schema::hasColumn('order_tracking', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('admin_users')) {
            Schema::table('admin_users', function (Blueprint $table) {
                foreach (['is_available', 'current_order_id'] as $col) {
                    if (Schema::hasColumn('admin_users', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
