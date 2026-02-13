<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tasty Wallet table
        Schema::create('ti_tasty_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('UGX');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('customer_id');
        });

        // Wallet Transactions table
        Schema::create('ti_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', ['credit', 'debit', 'refund', 'bonus', 'cashback'])->default('credit');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description');
            $table->string('reference')->nullable();
            $table->string('reference_type')->nullable(); // 'order', 'subscription', 'topup', etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed'])->default('completed');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('wallet_id');
            $table->index(['reference_type', 'reference_id']);
            $table->index('status');
            $table->index('created_at');

            $table->foreign('wallet_id')->references('id')->on('ti_tasty_wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ti_wallet_transactions');
        Schema::dropIfExists('ti_tasty_wallets');
    }
};
