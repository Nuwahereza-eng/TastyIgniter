<?php

namespace App\Services;

use App\Models\GroupOrder;
use Igniter\Cart\Facades\Cart;
use Igniter\User\Facades\Auth;

/**
 * Copies the customer's cart into their group order participant row while
 * a group order is active in the session (set by "Add items" on the group
 * order page). Runs on every cart change and from the "Done Adding" button.
 */
class GroupOrderCartSync
{
    /**
     * @return array{success: bool, error?: string, status?: int, items_count?: int, subtotal?: float, group_total?: float}
     */
    public function syncActive(): array
    {
        $groupOrderId = session('active_group_order_id');
        if (!$groupOrderId) {
            return ['success' => false, 'error' => 'No active group order', 'status' => 400];
        }

        $customer = Auth::customer();
        if (!$customer) {
            return ['success' => false, 'error' => 'You must be logged in', 'status' => 401];
        }

        $groupOrder = GroupOrder::find($groupOrderId);
        if (!$groupOrder) {
            session()->forget('active_group_order_id');

            return ['success' => false, 'error' => 'Group order not found', 'status' => 404];
        }

        // Once the group is closed (someone has paid) or ordered, the bill
        // must not change underneath the payers.
        if ($groupOrder->status !== GroupOrder::STATUS_OPEN) {
            return ['success' => false, 'error' => 'This group order is no longer accepting changes', 'status' => 409];
        }

        $participant = $groupOrder->participants()->where('customer_id', $customer->getKey())->first();
        if (!$participant) {
            return ['success' => false, 'error' => 'You are not a participant', 'status' => 403];
        }

        $items = [];
        $subtotal = 0;
        foreach (Cart::content() as $item) {
            $options = [];
            foreach ($item->options ?? [] as $option) {
                $options[] = ['name' => $option->name ?? '', 'price' => (float)($option->price ?? 0)];
            }

            $items[] = [
                'row_id' => $item->rowId,
                'menu_item_id' => $item->id,
                'name' => $item->name,
                'price' => (float)$item->price,
                'quantity' => $item->qty,
                'options' => $options,
                'comment' => $item->comment ?? null,
            ];
            $subtotal += $item->subtotal();
        }

        $participant->cart_items = $items;
        $participant->subtotal = $subtotal;
        $participant->save();

        $groupOrder->total_amount = $groupOrder->calculateTotal();
        $groupOrder->save();

        return [
            'success' => true,
            'items_count' => count($items),
            'subtotal' => (float)$subtotal,
            'group_total' => (float)$groupOrder->total_amount,
        ];
    }
}
