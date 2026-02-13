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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('tx_ref')->unique();
            $table->string('flw_ref')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('UGX');
            $table->string('payment_method'); // mtn, airtel, card
            $table->string('payment_type'); // order, subscription
            $table->unsignedBigInteger('reference_id')->nullable(); // order_id or subscription_id
            $table->enum('status', ['pending', 'successful', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->string('provider')->default('flutterwave'); // flutterwave, demo
            $table->json('metadata')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('tx_ref');
            $table->index('status');
            $table->index(['payment_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
