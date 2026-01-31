<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GroupOrder;
use App\Models\GroupOrderParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GroupOrderWebController extends Controller
{
    /**
     * Get current customer ID from TastyIgniter session
     */
    private function getCustomerId(): ?int
    {
        // Try TastyIgniter's customer auth
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            // Check if logged in first
            $isLogged = \Igniter\User\Facades\Auth::check();
            $customer = \Igniter\User\Facades\Auth::customer();
            
            \Log::info('GroupOrder getCustomerId', [
                'isLogged' => $isLogged,
                'customer_id' => $customer ? $customer->customer_id : null,
                'customer_name' => $customer ? $customer->full_name : null,
                'session_id' => session()->getId(),
                'guard_name' => config('igniter-auth.guards.web', 'web'),
            ]);
            
            if ($customer) {
                return $customer->customer_id;
            }
        }
        
        return null;
    }

    /**
     * Get customer name from TastyIgniter session
     */
    private function getCustomerName(): ?string
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->full_name;
            }
        }
        
        return null;
    }

    /**
     * Get customer email from TastyIgniter session
     */
    private function getCustomerEmail(): ?string
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->email;
            }
        }
        
        return null;
    }

    /**
     * List group orders for current user (hosted + participating)
     */
    public function index(): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'error' => 'You must be logged in to view group orders',
            ], 401);
        }

        // Get orders where user is host
        $hosted = GroupOrder::where('host_customer_id', $customerId)
            ->where('status', GroupOrder::STATUS_OPEN)
            ->with('participants')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get orders where user is participant (not host)
        $participantIds = GroupOrderParticipant::where('customer_id', $customerId)
            ->pluck('group_order_id');
            
        $participating = GroupOrder::whereIn('id', $participantIds)
            ->where('host_customer_id', '!=', $customerId)
            ->where('status', GroupOrder::STATUS_OPEN)
            ->with('participants')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'hosted' => $hosted,
            'participating' => $participating,
        ]);
    }

    /**
     * Create a new group order
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('GroupOrder store called', [
            'request_data' => $request->all(),
            'session_id' => session()->getId(),
        ]);
        
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            \Log::warning('GroupOrder: Customer not logged in', [
                'session_id' => session()->getId(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'You must be logged in to create a group order',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required_without:name|string|max:255',
            'name' => 'required_without:title|string|max:255',
            'location_id' => 'nullable|integer',
            'deadline_at' => 'nullable|date',
            'split_method' => 'nullable|in:equal,individual,host,by_item,host_pays',
        ]);

        if ($validator->fails()) {
            \Log::warning('GroupOrder validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Map split_method from frontend values to DB enum
        $splitMethod = $request->split_method ?? 'individual';
        if ($splitMethod === 'by_item') $splitMethod = 'individual';
        if ($splitMethod === 'host_pays') $splitMethod = 'host';

        try {
            $groupOrder = GroupOrder::create([
                'host_customer_id' => $customerId,
                'code' => GroupOrder::generateCode(),
                'name' => $request->title ?? $request->name,
                'location_id' => $request->location_id ?? null,
                'deadline' => $request->deadline_at,
                'split_method' => $splitMethod,
                'status' => GroupOrder::STATUS_OPEN,
            ]);

            // Add host as first participant
            GroupOrderParticipant::create([
                'group_order_id' => $groupOrder->id,
                'customer_id' => $customerId,
                'guest_name' => $this->getCustomerName() ?? 'Host',
                'guest_email' => $this->getCustomerEmail(),
                'is_host' => true,
            ]);

            // Store active group order in session
            session(['active_group_order_id' => $groupOrder->id]);

            return response()->json([
                'success' => true,
                'group_order' => $groupOrder->load('participants'),
                'invite_code' => $groupOrder->code,
            ]);
        } catch (\Exception $e) {
            \Log::error('GroupOrder creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to create group order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific group order
     */
    public function show(int $id): JsonResponse
    {
        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::with('participants')->find($id);
        
        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        // Check if user is host or participant
        $isParticipant = $groupOrder->participants()->where('customer_id', $customerId)->exists();
        if ($groupOrder->host_customer_id !== $customerId && !$isParticipant) {
            return response()->json([
                'success' => false,
                'error' => 'Access denied',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder,
        ]);
    }

    /**
     * Join a group order by invite code
     */
    public function join(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'invite_code' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Clean up invite code (remove dashes, uppercase)
        $inviteCode = strtoupper(str_replace('-', '', $request->invite_code));
        
        $groupOrder = GroupOrder::where('code', $inviteCode)->first();

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found. Please check the code.',
            ], 404);
        }

        if (!$groupOrder->isAcceptingParticipants()) {
            return response()->json([
                'success' => false,
                'error' => 'This group order is no longer accepting participants',
            ], 400);
        }

        $customerId = $this->getCustomerId();
        $customerName = $this->getCustomerName() ?? $request->name ?? 'Guest';
        $customerEmail = $this->getCustomerEmail();

        // Check if already participating
        $existingParticipant = null;
        if ($customerId) {
            $existingParticipant = $groupOrder->participants()
                ->where('customer_id', $customerId)
                ->first();
        }

        if ($existingParticipant) {
            // Store active group order in session
            session(['active_group_order_id' => $groupOrder->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'You are already in this group order',
                'group_order' => $groupOrder->load('participants'),
                'participant' => $existingParticipant,
            ]);
        }

        $participant = GroupOrderParticipant::create([
            'group_order_id' => $groupOrder->id,
            'customer_id' => $customerId,
            'guest_name' => $customerName,
            'guest_email' => $customerEmail,
            'is_host' => false,
        ]);

        // Store active group order in session
        session(['active_group_order_id' => $groupOrder->id]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the group order!',
            'group_order' => $groupOrder->load('participants'),
            'participant' => $participant,
        ]);
    }

    /**
     * Get current active group order from session
     */
    public function current(): JsonResponse
    {
        $groupOrderId = session('active_group_order_id');
        
        if (!$groupOrderId) {
            return response()->json([
                'success' => true,
                'active' => false,
                'group_order' => null,
            ]);
        }

        $groupOrder = GroupOrder::with('participants')->find($groupOrderId);
        
        if (!$groupOrder || $groupOrder->status === GroupOrder::STATUS_ORDERED || $groupOrder->status === GroupOrder::STATUS_CANCELLED) {
            session()->forget('active_group_order_id');
            return response()->json([
                'success' => true,
                'active' => false,
                'group_order' => null,
            ]);
        }

        $customerId = $this->getCustomerId();
        $participant = $groupOrder->participants()->where('customer_id', $customerId)->first();

        return response()->json([
            'success' => true,
            'active' => true,
            'group_order' => $groupOrder,
            'my_participant' => $participant,
            'is_host' => $groupOrder->host_customer_id === $customerId,
        ]);
    }

    /**
     * Set active group order in session
     */
    public function setActive(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_order_id' => 'required|integer|exists:group_orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::find($request->group_order_id);

        // Verify user is participant
        $isParticipant = $groupOrder->participants()->where('customer_id', $customerId)->exists();
        if ($groupOrder->host_customer_id !== $customerId && !$isParticipant) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant in this group order',
            ], 403);
        }

        session(['active_group_order_id' => $groupOrder->id]);

        return response()->json([
            'success' => true,
            'message' => 'Group order activated',
            'group_order' => $groupOrder->load('participants'),
        ]);
    }

    /**
     * Clear active group order from session
     */
    public function clearActive(): JsonResponse
    {
        session()->forget('active_group_order_id');

        return response()->json([
            'success' => true,
            'message' => 'Group order cleared',
        ]);
    }

    /**
     * Update participant's items (for adding items from menu)
     */
    public function updateItems(Request $request): JsonResponse
    {
        $groupOrderId = session('active_group_order_id');
        
        if (!$groupOrderId) {
            return response()->json([
                'success' => false,
                'error' => 'No active group order',
            ], 400);
        }

        $groupOrder = GroupOrder::find($groupOrderId);
        if (!$groupOrder) {
            session()->forget('active_group_order_id');
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        $customerId = $this->getCustomerId();
        $participant = $groupOrder->participants()->where('customer_id', $customerId)->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant in this group order',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $participant->cart_items = $request->items;
        $participant->calculateSubtotal();
        $participant->save();

        // Update group order total
        $groupOrder->total_amount = $groupOrder->calculateTotal();
        $groupOrder->save();

        return response()->json([
            'success' => true,
            'participant' => $participant,
            'group_total' => $groupOrder->total_amount,
        ]);
    }

    /**
     * Mark participant as ready (finished selecting items)
     */
    public function markReady(): JsonResponse
    {
        $groupOrderId = session('active_group_order_id');
        
        if (!$groupOrderId) {
            return response()->json([
                'success' => false,
                'error' => 'No active group order',
            ], 400);
        }

        $groupOrder = GroupOrder::find($groupOrderId);
        $customerId = $this->getCustomerId();
        $participant = $groupOrder->participants()->where('customer_id', $customerId)->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant',
            ], 403);
        }

        // Participant is "ready" when they have cart_items
        // This is determined by the status getter in the model

        return response()->json([
            'success' => true,
            'message' => 'Marked as ready',
            'participant' => $participant,
        ]);
    }

    /**
     * Finalize group order (host only)
     */
    public function finalize(int $id): JsonResponse
    {
        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::find($id);

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        if ($groupOrder->host_customer_id !== $customerId) {
            return response()->json([
                'success' => false,
                'error' => 'Only the host can finalize the group order',
            ], 403);
        }

        if ($groupOrder->status !== GroupOrder::STATUS_OPEN) {
            return response()->json([
                'success' => false,
                'error' => 'Group order cannot be finalized in current state',
            ], 400);
        }

        $groupOrder->status = GroupOrder::STATUS_CLOSED;
        $groupOrder->total_amount = $groupOrder->calculateTotal();
        $groupOrder->save();

        return response()->json([
            'success' => true,
            'message' => 'Group order finalized! Ready for checkout.',
            'group_order' => $groupOrder->load('participants'),
        ]);
    }

    /**
     * Cancel group order (host only)
     */
    public function cancel(int $id): JsonResponse
    {
        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::find($id);

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        if ($groupOrder->host_customer_id !== $customerId) {
            return response()->json([
                'success' => false,
                'error' => 'Only the host can cancel the group order',
            ], 403);
        }

        if ($groupOrder->status === GroupOrder::STATUS_ORDERED) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot cancel completed orders',
            ], 400);
        }

        $groupOrder->status = GroupOrder::STATUS_CANCELLED;
        $groupOrder->save();

        // Clear session if this was the active group order
        if (session('active_group_order_id') == $id) {
            session()->forget('active_group_order_id');
        }

        return response()->json([
            'success' => true,
            'message' => 'Group order cancelled',
        ]);
    }

    /**
     * Leave a group order (for participants, not host)
     */
    public function leave(int $id): JsonResponse
    {
        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::find($id);

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        if ($groupOrder->host_customer_id === $customerId) {
            return response()->json([
                'success' => false,
                'error' => 'Host cannot leave the group order. Cancel it instead.',
            ], 400);
        }

        $participant = $groupOrder->participants()->where('customer_id', $customerId)->first();
        
        if (!$participant) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant in this group order',
            ], 404);
        }

        $participant->delete();

        // Recalculate total
        $groupOrder->total_amount = $groupOrder->calculateTotal();
        $groupOrder->save();

        // Clear session if this was the active group order
        if (session('active_group_order_id') == $id) {
            session()->forget('active_group_order_id');
        }

        return response()->json([
            'success' => true,
            'message' => 'Left the group order',
        ]);
    }

    /**
     * Sync current cart to group order participant
     */
    public function syncCart(): JsonResponse
    {
        $groupOrderId = session('active_group_order_id');
        
        if (!$groupOrderId) {
            return response()->json([
                'success' => false,
                'error' => 'No active group order',
            ], 400);
        }

        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'error' => 'You must be logged in',
            ], 401);
        }

        $groupOrder = GroupOrder::find($groupOrderId);
        if (!$groupOrder) {
            session()->forget('active_group_order_id');
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        $participant = $groupOrder->participants()->where('customer_id', $customerId)->first();
        if (!$participant) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant',
            ], 403);
        }

        // Get cart from TastyIgniter Cart facade
        try {
            $cart = \Igniter\Cart\Facades\Cart::content();
            $items = [];
            $subtotal = 0;
            
            foreach ($cart as $item) {
                $itemData = [
                    'row_id' => $item->rowId,
                    'menu_item_id' => $item->id,
                    'name' => $item->name,
                    'price' => (float) $item->price,
                    'quantity' => $item->qty,
                    'options' => [],
                    'comment' => $item->comment ?? null,
                ];
                
                // Get options if available
                if ($item->options && count($item->options) > 0) {
                    foreach ($item->options as $option) {
                        $itemData['options'][] = [
                            'name' => $option->name ?? '',
                            'price' => (float) ($option->price ?? 0),
                        ];
                    }
                }
                
                $items[] = $itemData;
                $subtotal += $item->price * $item->qty;
            }
            
            $participant->cart_items = $items;
            $participant->subtotal = $subtotal;
            $participant->save();

            // Update group order total
            $groupOrder->total_amount = $groupOrder->calculateTotal();
            $groupOrder->save();

            return response()->json([
                'success' => true,
                'items_count' => count($items),
                'subtotal' => $subtotal,
                'group_total' => $groupOrder->total_amount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Group order cart sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to sync cart',
            ], 500);
        }
    }

    /**
     * Get combined cart for checkout (host only, when finalizing)
     */
    public function getCombinedCart(int $id): JsonResponse
    {
        $customerId = $this->getCustomerId();
        $groupOrder = GroupOrder::with('participants')->find($id);

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Group order not found',
            ], 404);
        }

        if ($groupOrder->host_customer_id !== $customerId) {
            return response()->json([
                'success' => false,
                'error' => 'Only the host can view combined cart',
            ], 403);
        }

        $combinedItems = [];
        $participantBreakdown = [];

        foreach ($groupOrder->participants as $participant) {
            $items = $participant->cart_items ?? [];
            $participantInfo = [
                'name' => $participant->guest_name,
                'is_host' => $participant->is_host,
                'subtotal' => $participant->subtotal ?? 0,
                'items' => $items,
            ];
            $participantBreakdown[] = $participantInfo;

            // Merge items into combined cart
            foreach ($items as $item) {
                $key = $item['menu_item_id'] . '_' . json_encode($item['options'] ?? []);
                if (isset($combinedItems[$key])) {
                    $combinedItems[$key]['quantity'] += $item['quantity'];
                } else {
                    $combinedItems[$key] = $item;
                }
            }
        }

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder,
            'combined_items' => array_values($combinedItems),
            'participant_breakdown' => $participantBreakdown,
            'total' => $groupOrder->total_amount,
        ]);
    }
}
