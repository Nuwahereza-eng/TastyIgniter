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
        // Skip if table already exists (created by a different migration)
        if (Schema::hasTable('group_orders')) {
            return;
        }
        
        Schema::create('group_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('host_customer_id')->nullable();
            $table->string('invite_code', 20)->unique();
            $table->string('title');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->dateTime('deadline_at')->nullable();
            $table->string('split_method', 20)->default('by_item'); // equal, by_item, host_pays
            $table->string('status', 20)->default('collecting'); // pending, collecting, ordering, completed, cancelled
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index('host_customer_id');
            $table->index('invite_code');
            $table->index('status');
        });

        // Skip if table already exists
        if (Schema::hasTable('group_order_participants')) {
            return;
        }
        
        Schema::create('group_order_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_order_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->json('items')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->string('status', 20)->default('joined'); // joined, selecting, ready, paid
            $table->dateTime('joined_at')->nullable();
            $table->timestamps();
            
            $table->foreign('group_order_id')->references('id')->on('group_orders')->onDelete('cascade');
            $table->index('group_order_id');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_order_participants');
        Schema::dropIfExists('group_orders');
    }
};
