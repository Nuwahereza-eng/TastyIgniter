<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The connection prefix (ti_) is applied by Laravel automatically, so 'customers'
        // resolves to ti_customers. Only add the foreign key when that table already exists
        // with a BIGINT UNSIGNED customer_id, otherwise MySQL rejects the constraint (or later
        // rejects the core make_primary_key_bigint_all_tables ALTER). This keeps the migration
        // independent of whether it runs before or after the core schema.
        $canReferenceCustomers = $this->customersKeyIsBigInt();

        // Tasty Wallets table
        Schema::create('tasty_wallets', function (Blueprint $table) use ($canReferenceCustomers) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('pin')->nullable(); // For wallet security
            $table->timestamps();
            
            if ($canReferenceCustomers) {
                $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            }
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

    protected function customersKeyIsBigInt(): bool
    {
        if (!Schema::hasTable('customers')) {
            return false;
        }

        foreach (Schema::getColumns('customers') as $column) {
            if ($column['name'] === 'customer_id') {
                $type = strtolower($column['type']);

                return str_contains($type, 'bigint') && str_contains($type, 'unsigned');
            }
        }

        return false;
    }

    public function down(): void
    {
        Schema::dropIfExists('tasty_wallet_transactions');
        Schema::dropIfExists('tasty_wallets');
    }
};
