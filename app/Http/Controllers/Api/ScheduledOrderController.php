<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduledOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduledOrderController extends Controller
{
    /**
     * Get all scheduled orders for the authenticated customer
     */
    public function index(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $scheduledOrders = ScheduledOrder::byCustomer($customerId)
            ->with('location')
            ->orderBy('scheduled_date', 'asc')
            ->orderBy('scheduled_time', 'asc')
            ->get();

        // Group by upcoming and past
        $upcoming = $scheduledOrders->filter(fn($o) => !$o->isScheduledTimePast());
        $past = $scheduledOrders->filter(fn($o) => $o->isScheduledTimePast());

        return response()->json([
            'success' => true,
            'upcoming' => $upcoming->values(),
            'past' => $past->values(),
            'recurring_count' => $scheduledOrders->where('is_recurring', true)->count(),
        ]);
    }

    /**
     * Create a new scheduled order
     */
    public function store(Request $request): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if (!$customerId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'location_id' => 'required|integer|exists:locations,location_id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|date_format:H:i',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|required_if:is_recurring,true|in:daily,weekly,biweekly,monthly',
            'cart_data' => 'nullable|array',
            'cart_data.items' => 'nullable|array',
            'cart_data.total' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $scheduledOrder = ScheduledOrder::create([
            'customer_id' => $customerId,
            'location_id' => $request->location_id,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'is_recurring' => $request->is_recurring ?? false,
            'recurring_frequency' => $request->recurring_frequency,
            'cart_data' => $request->cart_data,
            'status' => ScheduledOrder::STATUS_PENDING,
        ]);

        // Calculate next occurrence if recurring
        if ($scheduledOrder->is_recurring) {
            $scheduledOrder->next_occurrence = $scheduledOrder->calculateNextOccurrence();
            $scheduledOrder->save();
        }

        return response()->json([
            'success' => true,
            'scheduled_order' => $scheduledOrder->load('location'),
        ], 201);
    }

    /**
     * Get a specific scheduled order
     */
    public function show(ScheduledOrder $scheduledOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if ($scheduledOrder->customer_id !== $customerId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json([
            'success' => true,
            'scheduled_order' => $scheduledOrder->load('location'),
        ]);
    }

    /**
     * Update a scheduled order
     */
    public function update(Request $request, ScheduledOrder $scheduledOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if ($scheduledOrder->customer_id !== $customerId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if ($scheduledOrder->status === ScheduledOrder::STATUS_COMPLETED) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot update completed orders',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'scheduled_date' => 'sometimes|date|after_or_equal:today',
            'scheduled_time' => 'sometimes|date_format:H:i',
            'is_recurring' => 'sometimes|boolean',
            'recurring_frequency' => 'nullable|in:daily,weekly,biweekly,monthly',
            'cart_data' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $scheduledOrder->fill($request->only([
            'scheduled_date',
            'scheduled_time',
            'is_recurring',
            'recurring_frequency',
            'cart_data',
        ]));

        // Recalculate next occurrence if recurring settings changed
        if ($request->has('is_recurring') || $request->has('recurring_frequency')) {
            $scheduledOrder->next_occurrence = $scheduledOrder->calculateNextOccurrence();
        }

        $scheduledOrder->save();

        return response()->json([
            'success' => true,
            'scheduled_order' => $scheduledOrder->load('location'),
        ]);
    }

    /**
     * Cancel a scheduled order
     */
    public function cancel(ScheduledOrder $scheduledOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if ($scheduledOrder->customer_id !== $customerId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (in_array($scheduledOrder->status, [ScheduledOrder::STATUS_COMPLETED, ScheduledOrder::STATUS_CANCELLED])) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot cancel this order',
            ], 400);
        }

        $scheduledOrder->status = ScheduledOrder::STATUS_CANCELLED;
        $scheduledOrder->save();

        return response()->json([
            'success' => true,
            'message' => 'Scheduled order cancelled',
        ]);
    }

    /**
     * Delete a scheduled order
     */
    public function destroy(ScheduledOrder $scheduledOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if ($scheduledOrder->customer_id !== $customerId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $scheduledOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Scheduled order deleted',
        ]);
    }

    /**
     * Get available time slots for a location and date
     */
    public function getTimeSlots(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate time slots (this can be enhanced to check location hours)
        $slots = [];
        $startHour = 8; // 8 AM
        $endHour = 21; // 9 PM
        
        for ($hour = $startHour; $hour <= $endHour; $hour++) {
            foreach (['00', '30'] as $minutes) {
                $time = sprintf('%02d:%s', $hour, $minutes);
                $label = date('g:i A', strtotime($time));
                
                // Check if slot is available (not fully booked)
                $existingCount = ScheduledOrder::where('location_id', $request->location_id)
                    ->where('scheduled_date', $request->date)
                    ->where('scheduled_time', $time)
                    ->whereIn('status', [ScheduledOrder::STATUS_PENDING, ScheduledOrder::STATUS_CONFIRMED])
                    ->count();

                $slots[] = [
                    'time' => $time,
                    'label' => $label,
                    'available' => $existingCount < 10, // Max 10 orders per slot
                    'bookings' => $existingCount,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'date' => $request->date,
            'slots' => $slots,
        ]);
    }

    /**
     * Skip next occurrence of recurring order
     */
    public function skipNext(ScheduledOrder $scheduledOrder): JsonResponse
    {
        $customerId = $this->getCustomerId();
        
        if ($scheduledOrder->customer_id !== $customerId) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (!$scheduledOrder->is_recurring) {
            return response()->json([
                'success' => false,
                'error' => 'Not a recurring order',
            ], 400);
        }

        $scheduledOrder->moveToNextOccurrence();

        return response()->json([
            'success' => true,
            'scheduled_order' => $scheduledOrder,
            'message' => 'Skipped to next occurrence',
        ]);
    }

    /**
     * Get the current customer ID
     */
    private function getCustomerId(): ?int
    {
        if (class_exists(\Igniter\User\Facades\Auth::class)) {
            $customer = \Igniter\User\Facades\Auth::customer();
            if ($customer) {
                return $customer->customer_id;
            }
        }

        if (Auth::check()) {
            return Auth::id();
        }

        return null;
    }
}
