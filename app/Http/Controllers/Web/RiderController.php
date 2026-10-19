<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrderTracking;
use App\Services\DeliveryLocator;
use Igniter\Cart\Models\Order;
use Igniter\User\Facades\AdminAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Mobile screen for riders (staff with the "delivery" role). Riders log in
 * through the normal admin login, then open /rider on their phone to see
 * their assigned orders and share their live position with the customer.
 */
class RiderController extends Controller
{
    /** Statuses a rider may set from the rider screen. */
    protected const RIDER_STATUSES = [
        OrderTracking::STATUS_PICKED_UP,
        OrderTracking::STATUS_ON_THE_WAY,
        OrderTracking::STATUS_NEARBY,
        OrderTracking::STATUS_DELIVERED,
    ];

    public function index()
    {
        if (!$this->rider()) {
            return redirect()->to(admin_url('login'));
        }

        return view('rider', ['rider' => $this->rider()]);
    }

    public function deliveries(DeliveryLocator $locator): JsonResponse
    {
        $rider = $this->rider();
        if (!$rider) {
            return response()->json(['success' => false, 'error' => 'Please log in'], 401);
        }

        $deliveries = OrderTracking::where('assigned_rider_id', $rider->getKey())
            ->whereNotIn('status', [OrderTracking::STATUS_DELIVERED, OrderTracking::STATUS_CANCELLED])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (OrderTracking $tracking) use ($locator) {
                $order = Order::with(['location', 'address'])->find($tracking->order_id);
                if (!$order) {
                    return null;
                }

                $address = $order->address;

                return [
                    'order_id' => $order->order_id,
                    'formatted_id' => 'UGA-'.str_pad($order->order_id, 5, '0', STR_PAD_LEFT),
                    'status' => $tracking->status,
                    'status_label' => $tracking->getStatusLabel(),
                    'customer_name' => trim($order->first_name.' '.$order->last_name),
                    'customer_phone' => $order->telephone,
                    'address' => $address ? trim(implode(', ', array_filter([$address->address_1, $address->address_2, $address->city]))) : null,
                    'comment' => $order->delivery_comment ?: $order->comment,
                    'total' => 'UGX '.number_format((float)$order->order_total),
                    'payment' => $order->payment,
                    'restaurant' => [
                        'name' => $order->location?->location_name,
                        'coordinates' => $locator->restaurantCoordinates($order),
                    ],
                    'delivery' => $locator->deliveryCoordinates($order, $tracking),
                ];
            })
            ->filter()
            ->values();

        return response()->json(['success' => true, 'deliveries' => $deliveries]);
    }

    public function updateLocation(Request $request, int $orderId): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $tracking = $this->ownedTracking($orderId);
        if (!$tracking) {
            return response()->json(['success' => false, 'error' => 'Delivery not found'], 404);
        }

        $tracking->updateLocation((float)$request->lat, (float)$request->lng, $this->etaMinutes($tracking, (float)$request->lat, (float)$request->lng));

        return response()->json(['success' => true, 'eta_minutes' => $tracking->eta_minutes]);
    }

    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $request->validate(['status' => 'required|in:'.implode(',', self::RIDER_STATUSES)]);

        $tracking = $this->ownedTracking($orderId);
        if (!$tracking) {
            return response()->json(['success' => false, 'error' => 'Delivery not found'], 404);
        }

        $tracking->status = $request->status;
        if ($request->status === OrderTracking::STATUS_PICKED_UP && !$tracking->rider_accepted_at) {
            $tracking->rider_accepted_at = now();
        }
        if ($request->status === OrderTracking::STATUS_DELIVERED) {
            $tracking->eta_minutes = 0;
        }
        $tracking->save();

        if ($request->status === OrderTracking::STATUS_DELIVERED) {
            DB::table('admin_users')
                ->where('user_id', $tracking->assigned_rider_id)
                ->update(['current_order_id' => null, 'is_available' => 1]);
        }

        return response()->json(['success' => true, 'status' => $tracking->status, 'status_label' => $tracking->getStatusLabel()]);
    }

    protected function rider()
    {
        $user = AdminAuth::getUser();

        return $user && ($user->role?->code === 'delivery' || $user->isSuperUser()) ? $user : null;
    }

    protected function ownedTracking(int $orderId): ?OrderTracking
    {
        $rider = $this->rider();
        if (!$rider) {
            return null;
        }

        return OrderTracking::where('order_id', $orderId)
            ->where('assigned_rider_id', $rider->getKey())
            ->first();
    }

    /**
     * Rough ETA from the straight-line distance to the delivery point at an
     * average boda-boda speed of 20 km/h through Kampala traffic.
     */
    protected function etaMinutes(OrderTracking $tracking, float $lat, float $lng): ?int
    {
        if (!$tracking->delivery_lat || !$tracking->delivery_lng) {
            return $tracking->eta_minutes;
        }

        $km = $this->distanceKm($lat, $lng, (float)$tracking->delivery_lat, (float)$tracking->delivery_lng);

        return max(1, (int)ceil($km * 1.3 / 20 * 60));
    }

    protected function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 6371 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
