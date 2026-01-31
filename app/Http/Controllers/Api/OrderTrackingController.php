<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OrderTrackingController extends Controller
{
    /**
     * Track an order by order ID (public - no auth required)
     */
    public function track(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required_without:order_hash|integer',
            'order_hash' => 'required_without:order_id|string',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the order
        $orderQuery = null;
        
        if (class_exists(\Igniter\Cart\Models\Order::class)) {
            $orderQuery = \Igniter\Cart\Models\Order::query();
            
            if ($request->order_id) {
                $orderQuery->where('order_id', $request->order_id);
            } elseif ($request->order_hash) {
                $orderQuery->where('hash', $request->order_hash);
            }

            // Optional phone verification for security
            if ($request->phone) {
                $orderQuery->where('telephone', $request->phone);
            }

            $order = $orderQuery->first();
        } else {
            // Fallback: just find tracking by order_id
            $tracking = OrderTracking::where('order_id', $request->order_id)->first();
            
            if (!$tracking) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'tracking' => $this->formatTrackingResponse($tracking),
            ]);
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'error' => 'Order not found. Please check your order ID and try again.',
            ], 404);
        }

        // Get or create tracking record
        $tracking = OrderTracking::firstOrCreate(
            ['order_id' => $order->order_id],
            ['status' => OrderTracking::STATUS_CONFIRMED]
        );

        // Get order items
        $orderItems = [];
        if (method_exists($order, 'getOrderMenus')) {
            foreach ($order->getOrderMenus() as $menu) {
                $orderItems[] = [
                    'name' => $menu->name,
                    'quantity' => $menu->quantity,
                    'price' => $menu->subtotal,
                ];
            }
        }

        // Get location/restaurant info
        $location = null;
        if ($order->location) {
            $location = [
                'name' => $order->location->location_name ?? 'Restaurant',
                'address' => $order->location->location_address ?? '',
                'phone' => $order->location->location_telephone ?? '',
            ];
        }

        // Format delivery address
        $deliveryAddress = '';
        if ($order->address) {
            $addr = $order->address;
            $deliveryAddress = is_object($addr) ? 
                ($addr->address_1 ?? '') . ', ' . ($addr->city ?? '') :
                (is_array($addr) ? ($addr['address_1'] ?? '') . ', ' . ($addr['city'] ?? '') : $addr);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->order_id,
                'formatted_id' => 'UGA-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                'hash' => $order->hash ?? null,
                'total' => $order->order_total ?? 0,
                'total_formatted' => 'UGX ' . number_format($order->order_total ?? 0),
                'created_at' => $order->created_at,
                'order_time' => $order->order_datetime ? $order->order_datetime->format('g:i A') : null,
                'order_date' => $order->order_datetime ? $order->order_datetime->format('M d, Y') : null,
                'delivery_address' => $deliveryAddress,
                'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                'customer_phone' => $order->telephone ?? '',
                'order_type' => $order->order_type ?? 'delivery',
                'payment_method' => $order->payment ?? 'N/A',
                'status_name' => $order->status ? $order->status->status_name : 'Pending',
                'items' => $orderItems,
                'items_summary' => $this->formatItemsSummary($orderItems),
            ],
            'location' => $location,
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Format items into a summary string
     */
    private function formatItemsSummary(array $items): string
    {
        if (empty($items)) return 'No items';
        
        $summary = array_map(function($item) {
            return ($item['quantity'] > 1 ? $item['quantity'] . 'x ' : '') . $item['name'];
        }, $items);
        
        return implode(', ', $summary);
    }

    /**
     * Get tracking status for an order
     */
    public function status(int $orderId): JsonResponse
    {
        $tracking = OrderTracking::byOrder($orderId)->first();

        if (!$tracking) {
            return response()->json([
                'success' => false,
                'error' => 'Tracking not found for this order',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Update tracking status (for admin/rider app)
     */
    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(OrderTracking::STATUS_LABELS)),
            'eta_minutes' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $tracking = OrderTracking::firstOrCreate(
            ['order_id' => $orderId],
            ['status' => OrderTracking::STATUS_CONFIRMED]
        );

        $tracking->status = $request->status;
        
        if ($request->has('eta_minutes')) {
            $tracking->eta_minutes = $request->eta_minutes;
        }

        $tracking->save();

        return response()->json([
            'success' => true,
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Assign rider to order (for admin)
     */
    public function assignRider(Request $request, int $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rider_name' => 'required|string|max:255',
            'rider_phone' => 'required|string|max:20',
            'rider_photo' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $tracking = OrderTracking::firstOrCreate(
            ['order_id' => $orderId],
            ['status' => OrderTracking::STATUS_CONFIRMED]
        );

        $tracking->rider_name = $request->rider_name;
        $tracking->rider_phone = $request->rider_phone;
        $tracking->rider_photo = $request->rider_photo;
        $tracking->save();

        return response()->json([
            'success' => true,
            'message' => 'Rider assigned successfully',
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Update rider location (for rider app)
     */
    public function updateLocation(Request $request, int $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'eta_minutes' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $tracking = OrderTracking::byOrder($orderId)->first();

        if (!$tracking) {
            return response()->json([
                'success' => false,
                'error' => 'Tracking not found',
            ], 404);
        }

        $tracking->updateLocation(
            $request->lat,
            $request->lng,
            $request->eta_minutes
        );

        return response()->json([
            'success' => true,
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Mark order as delivered
     */
    public function markDelivered(int $orderId): JsonResponse
    {
        $tracking = OrderTracking::byOrder($orderId)->first();

        if (!$tracking) {
            return response()->json([
                'success' => false,
                'error' => 'Tracking not found',
            ], 404);
        }

        $tracking->status = OrderTracking::STATUS_DELIVERED;
        $tracking->eta_minutes = 0;
        $tracking->save();

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered',
            'tracking' => $this->formatTrackingResponse($tracking),
        ]);
    }

    /**
     * Get all active deliveries (for admin dashboard)
     */
    public function activeDeliveries(): JsonResponse
    {
        $deliveries = OrderTracking::active()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($tracking) => $this->formatTrackingResponse($tracking, true));

        return response()->json([
            'success' => true,
            'count' => $deliveries->count(),
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Get all statuses with labels
     */
    public function statuses(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'statuses' => array_map(function ($key, $label) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'description' => OrderTracking::STATUS_DESCRIPTIONS[$key] ?? '',
                ];
            }, array_keys(OrderTracking::STATUS_LABELS), OrderTracking::STATUS_LABELS),
        ]);
    }

    /**
     * Format tracking response
     */
    private function formatTrackingResponse(OrderTracking $tracking, bool $includeOrder = false): array
    {
        $response = [
            'id' => $tracking->id,
            'order_id' => $tracking->order_id,
            'status' => $tracking->status,
            'status_label' => $tracking->getStatusLabel(),
            'status_description' => $tracking->getStatusDescription(),
            'progress_percentage' => $tracking->getProgressPercentage(),
            'is_in_transit' => $tracking->isInTransit(),
            'is_delivered' => $tracking->isDelivered(),
            'eta_minutes' => $tracking->eta_minutes,
            'rider' => $tracking->hasRider() ? [
                'name' => $tracking->rider_name,
                'phone' => $tracking->rider_phone,
                'photo' => $tracking->rider_photo,
            ] : null,
            'location' => ($tracking->current_lat && $tracking->current_lng) ? [
                'lat' => (float) $tracking->current_lat,
                'lng' => (float) $tracking->current_lng,
            ] : null,
            'updated_at' => $tracking->updated_at,
        ];

        if ($includeOrder && $tracking->order) {
            $response['order'] = [
                'id' => $tracking->order->order_id,
                'customer_name' => $tracking->order->first_name . ' ' . $tracking->order->last_name,
                'total' => $tracking->order->order_total,
                'address' => $tracking->order->address,
            ];
        }

        return $response;
    }

    /**
     * List all orders for management (admin)
     */
    public function listOrders(Request $request): JsonResponse
    {
        if (!class_exists(\Igniter\Cart\Models\Order::class)) {
            return response()->json([
                'success' => false,
                'error' => 'Order system not available',
            ], 500);
        }

        $limit = $request->get('limit', 50);
        $status = $request->get('status');

        $query = \Igniter\Cart\Models\Order::with(['location', 'status'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        $orders = $query->get();

        $formattedOrders = $orders->map(function ($order) {
            // Get tracking info
            $tracking = OrderTracking::where('order_id', $order->order_id)->first();
            
            // Get order items
            $items = [];
            if (method_exists($order, 'getOrderMenus')) {
                $items = collect($order->getOrderMenus())->pluck('name')->toArray();
            }

            // Format address
            $address = '';
            if ($order->address) {
                $addr = $order->address;
                $address = is_object($addr) ? 
                    ($addr->address_1 ?? '') . ', ' . ($addr->city ?? '') :
                    (is_array($addr) ? ($addr['address_1'] ?? '') . ', ' . ($addr['city'] ?? '') : $addr);
            }

            return [
                'id' => $order->order_id,
                'formatted_id' => 'UGA-' . str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                'phone' => $order->telephone ?? '',
                'total' => $order->order_total ?? 0,
                'total_formatted' => 'UGX ' . number_format($order->order_total ?? 0),
                'status' => $order->status ? $order->status->status_name : 'Pending',
                'tracking_status' => $tracking ? $tracking->status : 'pending',
                'address' => $address,
                'location' => $order->location ? $order->location->location_name : 'Restaurant',
                'items' => implode(', ', $items),
                'created_at' => $order->created_at,
                'order_time' => $order->created_at ? $order->created_at->format('g:i A') : '',
                'order_date' => $order->created_at ? $order->created_at->format('M d, Y') : '',
                'rider' => $tracking ? [
                    'name' => $tracking->rider_name,
                    'phone' => $tracking->rider_phone,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $formattedOrders,
            'total' => $orders->count(),
        ]);
    }
}
