<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupOrder;
use App\Models\GroupOrderParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupOrderController extends Controller
{
    /**
     * Get all group orders for the authenticated customer
     */
    public function index(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $groupOrders = GroupOrder::byHost($customerId)
            ->with(['participants', 'location'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Also get group orders where user is a participant
        $participatingIn = GroupOrderParticipant::where('customer_id', $customerId)
            ->with('groupOrder.host', 'groupOrder.participants')
            ->get()
            ->pluck('groupOrder')
            ->filter();

        return response()->json([
            'success' => true,
            'hosted' => $groupOrders,
            'participating' => $participatingIn,
        ]);
    }

    /**
     * Create a new group order
     */
    public function store(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,location_id',
            'deadline_at' => 'required|date|after:now',
            'split_method' => 'required|in:equal,by_item,host_pays',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $groupOrder = GroupOrder::create([
            'host_customer_id' => $customerId,
            'title' => $request->title,
            'location_id' => $request->location_id,
            'deadline_at' => $request->deadline_at,
            'split_method' => $request->split_method,
            'status' => GroupOrder::STATUS_COLLECTING,
        ]);

        // Add host as first participant
        GroupOrderParticipant::create([
            'group_order_id' => $groupOrder->id,
            'customer_id' => $customerId,
            'name' => $request->user()->full_name ?? 'Host',
            'status' => GroupOrderParticipant::STATUS_SELECTING,
        ]);

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder->load('participants'),
            'share_link' => $groupOrder->getShareLink(),
            'invite_code' => $groupOrder->invite_code,
        ], 201);
    }

    /**
     * Get a specific group order
     */
    public function show(GroupOrder $groupOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        // Check if user is host or participant
        $isHost = $groupOrder->host_customer_id === $customerId;
        $isParticipant = $groupOrder->participants()->where('customer_id', $customerId)->exists();

        if (!$isHost && !$isParticipant) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder->load(['participants', 'location', 'host']),
            'is_host' => $isHost,
            'is_accepting' => $groupOrder->isAcceptingParticipants(),
        ]);
    }

    /**
     * Join a group order via invite code
     */
    public function join(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'invite_code' => 'required|string',
            'name' => 'required_without:customer_id|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $groupOrder = GroupOrder::where('invite_code', strtoupper($request->invite_code))->first();

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid invite code',
            ], 404);
        }

        if (!$groupOrder->isAcceptingParticipants()) {
            return response()->json([
                'success' => false,
                'error' => 'This group order is no longer accepting participants',
            ], 400);
        }

        $customerId = $this->getCustomerId();

        // Check if already participating
        $existingParticipant = $groupOrder->participants()
            ->where(function ($query) use ($customerId, $request) {
                if ($customerId) {
                    $query->where('customer_id', $customerId);
                } else {
                    $query->where('phone', $request->phone);
                }
            })
            ->first();

        if ($existingParticipant) {
            return response()->json([
                'success' => false,
                'error' => 'You are already participating in this group order',
            ], 400);
        }

        $participant = GroupOrderParticipant::create([
            'group_order_id' => $groupOrder->id,
            'customer_id' => $customerId,
            'name' => $request->name ?? Auth::user()->full_name ?? 'Guest',
            'phone' => $request->phone,
            'status' => GroupOrderParticipant::STATUS_SELECTING,
        ]);

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder->load('participants'),
            'participant' => $participant,
        ]);
    }

    /**
     * Update participant's items
     */
    public function updateItems(Request $request, GroupOrder $groupOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        $participant = $groupOrder->participants()
            ->where('customer_id', $customerId)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Not a participant'], 403);
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

        $participant->items = $request->items;
        $participant->calculateSubtotal();
        $participant->status = GroupOrderParticipant::STATUS_READY;
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
     * Finalize group order (host only)
     */
    public function finalize(GroupOrder $groupOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if ($groupOrder->host_customer_id !== $customerId) {
            return response()->json(['error' => 'Only host can finalize'], 403);
        }

        if ($groupOrder->status !== GroupOrder::STATUS_COLLECTING) {
            return response()->json([
                'success' => false,
                'error' => 'Group order cannot be finalized in current state',
            ], 400);
        }

        // Check if all participants are ready
        $notReady = $groupOrder->participants()
            ->whereNotIn('status', [GroupOrderParticipant::STATUS_READY, GroupOrderParticipant::STATUS_PAID])
            ->count();

        if ($notReady > 0) {
            return response()->json([
                'success' => false,
                'error' => 'Some participants have not finished selecting items',
                'not_ready_count' => $notReady,
            ], 400);
        }

        $groupOrder->status = GroupOrder::STATUS_ORDERING;
        $groupOrder->total_amount = $groupOrder->calculateTotal();
        $groupOrder->save();

        return response()->json([
            'success' => true,
            'group_order' => $groupOrder->load('participants'),
        ]);
    }

    /**
     * Cancel group order (host only)
     */
    public function cancel(GroupOrder $groupOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();

        if ($groupOrder->host_customer_id !== $customerId) {
            return response()->json(['error' => 'Only host can cancel'], 403);
        }

        if ($groupOrder->status === GroupOrder::STATUS_COMPLETED) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot cancel completed orders',
            ], 400);
        }

        $groupOrder->status = GroupOrder::STATUS_CANCELLED;
        $groupOrder->save();

        return response()->json([
            'success' => true,
            'message' => 'Group order cancelled',
        ]);
    }

    /**
     * Get the current customer ID
     */
    private function getCustomerId(): ?int
    {
        // Try TastyIgniter's customer auth first
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }

        // Fallback to Laravel auth
        if (Auth::check()) {
            return Auth::id();
        }

        return null;
    }
}
