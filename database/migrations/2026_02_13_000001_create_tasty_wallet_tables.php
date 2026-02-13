<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tasty Wallets table
        Schema::create('tasty_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('pin')->nullable(); // For wallet security
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        });
        
        // Wallet Transactions table
        Schema::create('tasty_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', ['deposit', 'withdrawal', 'payment', 'refund', 'cashback', 'bonus']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('reference')->unique();
            $table->string('description')->nullable();
            $table->string('payment_method')->nullable(); // mobilemoney, flutterwave, etc
            $table->string('external_reference')->nullable(); // External payment reference
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('wallet_id')->references('id')->on('tasty_wallets')->onDelete('cascade');
            $table->index(['wallet_id', 'created_at']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasty_wallet_transactions');
        Schema::dropIfExists('tasty_wallets');
    }
};
