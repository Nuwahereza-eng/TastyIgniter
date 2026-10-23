<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('loyalty_accounts')) {
            Schema::create('loyalty_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->unique();
                $table->unsignedInteger('points_balance')->default(0);
                $table->unsignedInteger('lifetime_points')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('loyalty_transactions')) {
            Schema::create('loyalty_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('loyalty_account_id');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('wallet_transaction_id')->nullable();
                // earn = order completed, redeem = converted to wallet credit,
                // reverse = order cancelled after its points were earned
                $table->string('type', 16);
                $table->integer('points');
                $table->unsignedInteger('balance_after');
                $table->string('description')->nullable();
                $table->timestamps();

                $table->foreign('loyalty_account_id')->references('id')->on('loyalty_accounts')->onDelete('cascade');
                // An order earns (and is reversed) at most once.
                $table->unique(['order_id', 'type']);
                $table->index(['loyalty_account_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('loyalty_accounts');
    }
};
