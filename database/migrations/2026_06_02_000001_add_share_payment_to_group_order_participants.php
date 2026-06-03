<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('group_order_participants', function (Blueprint $table) {
            if (!Schema::hasColumn('group_order_participants', 'share_amount')) {
                $table->decimal('share_amount', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('group_order_participants', 'payment_tx_ref')) {
                $table->string('payment_tx_ref', 64)->nullable()->after('has_paid');
            }
            if (!Schema::hasColumn('group_order_participants', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_tx_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('group_order_participants', function (Blueprint $table) {
            foreach (['share_amount', 'payment_tx_ref', 'paid_at'] as $col) {
                if (Schema::hasColumn('group_order_participants', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
