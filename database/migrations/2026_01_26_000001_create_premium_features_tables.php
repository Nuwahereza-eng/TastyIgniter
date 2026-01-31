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
        // Group Orders table
        Schema::create('group_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // Invite code like ABCD-1234
            $table->string('name'); // Group name
            $table->unsignedBigInteger('host_customer_id'); // Creator
            $table->unsignedBigInteger('location_id')->nullable(); // Restaurant
            $table->enum('split_method', ['equal', 'individual', 'host'])->default('individual');
            $table->dateTime('deadline')->nullable();
            $table->enum('status', ['open', 'closed', 'ordered', 'cancelled'])->default('open');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('order_id')->nullable(); // Final order ID
            $table->timestamps();
            
            $table->index('code');
            $table->index('host_customer_id');
            $table->index('status');
        });

        // Group Order Participants
        Schema::create('group_order_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_order_id');
            $table->unsignedBigInteger('customer_id')->nullable(); // Null for guests
            $table->string('guest_name')->nullable(); // For non-logged-in users
            $table->string('guest_email')->nullable();
            $table->boolean('is_host')->default(false);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->boolean('has_paid')->default(false);
            $table->json('cart_items')->nullable(); // Store cart items as JSON
            $table->timestamps();
            
            $table->foreign('group_order_id')->references('id')->on('group_orders')->onDelete('cascade');
            $table->index('group_order_id');
            $table->index('customer_id');
        });

        // Scheduled Orders table
        Schema::create('scheduled_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('location_id');
            $table->dateTime('scheduled_date');
            $table->string('scheduled_time', 10); // e.g., "14:00"
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['daily', 'weekly', 'biweekly', 'monthly'])->nullable();
            $table->integer('recurring_count')->nullable(); // How many times to repeat
            $table->integer('recurring_completed')->default(0);
            $table->enum('status', ['pending', 'processed', 'cancelled'])->default('pending');
            $table->json('cart_items')->nullable(); // Saved cart items
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('scheduled_date');
            $table->index('status');
        });

        // Subscription Plans table
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Premium, Family
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Weekly price
            $table->enum('billing_period', ['weekly', 'monthly'])->default('weekly');
            $table->integer('meals_per_period'); // 5, 10, 21
            $table->boolean('free_delivery')->default(true);
            $table->boolean('express_delivery')->default(false);
            $table->boolean('premium_restaurants')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->integer('family_members')->default(1); // For family sharing
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Customer Subscriptions table
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->dateTime('started_at');
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('preferred_delivery_time', 10)->nullable();
            $table->integer('meals_remaining')->default(0);
            $table->integer('meals_used')->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
            
            $table->foreign('plan_id')->references('id')->on('subscription_plans');
            $table->index('customer_id');
            $table->index('status');
        });

        // Subscription Meals (tracking used meals)
        Schema::create('subscription_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->dateTime('used_at');
            $table->timestamps();
            
            $table->foreign('subscription_id')->references('id')->on('customer_subscriptions')->onDelete('cascade');
        });

        // Order Tracking Updates
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('status', [
                'placed',
                'confirmed',
                'preparing',
                'ready',
                'picked_up',
                'on_the_way',
                'arriving',
                'delivered',
                'cancelled'
            ]);
            $table->text('message')->nullable();
            $table->decimal('rider_latitude', 10, 8)->nullable();
            $table->decimal('rider_longitude', 11, 8)->nullable();
            $table->integer('eta_minutes')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('created_at');
        });

        // Riders table (for order tracking)
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('photo')->nullable();
            $table->string('vehicle_type')->default('motorcycle'); // motorcycle, bicycle, car
            $table->string('vehicle_plate')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_deliveries')->default(0);
            $table->boolean('is_available')->default(true);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamps();
        });

        // Order Rider Assignment
        Schema::create('order_riders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('rider_id');
            $table->dateTime('assigned_at');
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
            
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_riders');
        Schema::dropIfExists('riders');
        Schema::dropIfExists('order_tracking');
        Schema::dropIfExists('subscription_meals');
        Schema::dropIfExists('customer_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('scheduled_orders');
        Schema::dropIfExists('group_order_participants');
        Schema::dropIfExists('group_orders');
    }
};
