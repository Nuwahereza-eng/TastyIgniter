<?php

namespace App\Listeners;

use App\Models\OrderTracking;
use App\Models\Subscription;
use Igniter\Cart\Models\Order;
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
            OrderTracking::firstOrCreate(
                ['order_id' => $order->order_id],
                ['status' => OrderTracking::STATUS_CONFIRMED]
            );
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
            }
        } catch (\Throwable $e) {
            Log::warning('SyncOrderTracking@onStatusAdded failed', [
                'order_id' => $order->order_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
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
