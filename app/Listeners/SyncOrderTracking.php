<?php

namespace App\Listeners;

use App\Models\OrderTracking;
use App\Models\Subscription;
use App\Services\DeliveryLocator;
use App\Services\LoyaltyService;
use Igniter\Cart\Models\Order;
use Igniter\Local\Facades\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Keeps the local `order_tracking` table in sync with the Igniter Cart
 * order lifecycle so the customer-facing track-order UI and the rider
 * dashboard always have an up-to-date row to read from.
 *
 * Hooks the two Igniter Cart system events that mark progress milestones:
 *  - `admin.order.paymentProcessed` => order is confirmed (after payment)
 *  - `igniter.cart.orderStatusAdded` => admin moved the order to a new status
 */
class SyncOrderTracking
{
    /**
     * Bootstrap a tracking row right after payment is processed.
     *
     * @param  mixed  ...$args  Event payload (Order is the first arg)
     */
    public function onPaymentProcessed(...$args): void
    {
        $order = $args[0] ?? null;
        if (!$order instanceof Order) {
            return;
        }

        try {
            $tracking = OrderTracking::firstOrCreate(
                ['order_id' => $order->order_id],
                ['status' => OrderTracking::STATUS_CONFIRMED]
            );

            $this->rememberCustomerPosition($order, $tracking);
        } catch (\Throwable $e) {
            Log::warning('SyncOrderTracking@onPaymentProcessed failed', [
                'order_id' => $order->order_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mirror admin status changes into the tracking row when the new status
     * maps to a known tracking state.
     */
    public function onStatusAdded(...$args): void
    {
        $order = $args[0] ?? null;
        $statusHistory = $args[1] ?? null;
        if (!$order instanceof Order) {
            return;
        }

        $statusName = null;
        if (is_object($statusHistory)) {
            $statusName = $statusHistory->status_name
                ?? ($statusHistory->status->status_name ?? null);
        }
        $statusName = $statusName ?? ($order->status->status_name ?? null);

        $mapped = OrderTracking::mapOrderStatusName($statusName);
        if (!$mapped) {
            return;
        }

        try {
            $tracking = OrderTracking::firstOrCreate(
                ['order_id' => $order->order_id],
                ['status' => $mapped]
            );
            if ($tracking->status !== $mapped) {
                $tracking->status = $mapped;
                $tracking->save();
            }

            // When a delivery completes, decrement the customer's active
            // subscription (if any) so meals_remaining reflects reality.
            if ($mapped === OrderTracking::STATUS_DELIVERED) {
                $this->maybeDeductSubscriptionMeal($order);
                app(LoyaltyService::class)->earnForOrder($order);
            }

            if ($mapped === OrderTracking::STATUS_CANCELLED) {
                app(LoyaltyService::class)->reverseForOrder($order);
            }
        } catch (\Throwable $e) {
            Log::warning('SyncOrderTracking@onStatusAdded failed', [
                'order_id' => $order->order_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * When staff assign an order to a user with the "delivery" role in the
     * admin panel, make that user the order's rider so they see it on the
     * rider screen and the customer sees their name on the tracking page.
     *
     * Event payload: [Order $order, AssignableLog $log]
     */
    public function onAssigned(...$args): void
    {
        $order = $args[0] ?? null;
        if (!$order instanceof Order) {
            return;
        }

        try {
            $rider = $order->assignee()->first();
            if (!$rider || ($rider->role?->code ?? null) !== 'delivery') {
                return;
            }

            $tracking = OrderTracking::firstOrCreate(
                ['order_id' => $order->order_id],
                ['status' => OrderTracking::STATUS_CONFIRMED]
            );

            if ((int)$tracking->assigned_rider_id === (int)$rider->getKey()) {
                return;
            }

            // Free the previously assigned rider, if any.
            if ($tracking->assigned_rider_id) {
                DB::table('admin_users')
                    ->where('user_id', $tracking->assigned_rider_id)
                    ->where('current_order_id', $order->order_id)
                    ->update(['current_order_id' => null, 'is_available' => 1]);
            }

            $tracking->update([
                'assigned_rider_id' => $rider->getKey(),
                'rider_name' => $rider->name,
                'rider_phone' => $rider->telephone ?? null,
                'rider_accepted_at' => null,
                'rider_rejected_at' => null,
            ]);

            DB::table('admin_users')
                ->where('user_id', $rider->getKey())
                ->update(['current_order_id' => $order->order_id, 'is_available' => 0]);
        } catch (\Throwable $e) {
            Log::warning('SyncOrderTracking@onAssigned failed', [
                'order_id' => $order->order_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Save the position the customer shared with "Use my location" as the
     * delivery point. Only available while handling the customer's own
     * checkout request (a payment webhook has no customer session).
     */
    protected function rememberCustomerPosition(Order $order, OrderTracking $tracking): void
    {
        if ($order->order_type !== 'delivery' || $tracking->delivery_lat || !app()->bound('location')) {
            return;
        }

        $position = Location::userPosition();
        if (!$position || !$position->hasCoordinates()) {
            return;
        }

        $coordinates = $position->getCoordinates();
        app(DeliveryLocator::class)->rememberCustomerPosition(
            $tracking,
            (float)$coordinates->getLatitude(),
            (float)$coordinates->getLongitude()
        );
    }

    /**
     * If the customer who owns this order has an active meal subscription,
     * record the delivery against it (and decrement meals_remaining).
     * Idempotent — safe to call repeatedly for the same order.
     */
    protected function maybeDeductSubscriptionMeal(Order $order): void
    {
        $customerId = $order->customer_id ?? null;
        if (!$customerId) {
            return;
        }

        $subscription = Subscription::byCustomer($customerId)
            ->active()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscription) {
            return;
        }

        $subscription->recordOrderDelivery((int) $order->order_id);
    }
}
