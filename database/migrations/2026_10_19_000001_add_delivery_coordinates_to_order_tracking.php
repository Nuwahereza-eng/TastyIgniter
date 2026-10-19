<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_tracking', function (Blueprint $table) {
            if (!Schema::hasColumn('order_tracking', 'delivery_lat')) {
                $table->decimal('delivery_lat', 10, 8)->nullable()->after('current_lng');
            }
            if (!Schema::hasColumn('order_tracking', 'delivery_lng')) {
                $table->decimal('delivery_lng', 11, 8)->nullable()->after('delivery_lat');
            }
            // gps = customer's device position at checkout, geocoded = from the address text
            if (!Schema::hasColumn('order_tracking', 'delivery_source')) {
                $table->string('delivery_source', 16)->nullable()->after('delivery_lng');
            }
            if (!Schema::hasColumn('order_tracking', 'location_updated_at')) {
                $table->timestamp('location_updated_at')->nullable()->after('delivery_source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_tracking', function (Blueprint $table) {
            foreach (['delivery_lat', 'delivery_lng', 'delivery_source', 'location_updated_at'] as $col) {
                if (Schema::hasColumn('order_tracking', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
