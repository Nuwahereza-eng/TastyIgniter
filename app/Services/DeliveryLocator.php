<?php

namespace App\Services;

use App\Models\OrderTracking;
use Igniter\Cart\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Resolves the map coordinates used by order tracking: the restaurant
 * (branch) position and the customer's delivery point.
 */
class DeliveryLocator
{
    /**
     * Restaurant position from the branch record, or null when the branch
     * has no coordinates saved.
     */
    public function restaurantCoordinates(Order $order): ?array
    {
        $location = $order->location;
        if (!$location || !$location->location_lat || !$location->location_lng) {
            return null;
        }

        return ['lat' => (float)$location->location_lat, 'lng' => (float)$location->location_lng];
    }

    /**
     * Delivery point for the order. Uses the coordinates saved on the
     * tracking row, otherwise geocodes the delivery address once and saves
     * the result so later polls do not call the geocoder again.
     */
    public function deliveryCoordinates(Order $order, OrderTracking $tracking): ?array
    {
        if ($tracking->delivery_lat && $tracking->delivery_lng) {
            return ['lat' => (float)$tracking->delivery_lat, 'lng' => (float)$tracking->delivery_lng];
        }

        if ($order->order_type !== 'delivery' || !$order->address) {
            return null;
        }

        foreach ($this->addressCandidates($order->address) as $candidate) {
            $coords = $this->geocode($candidate);
            if ($coords) {
                $tracking->update([
                    'delivery_lat' => $coords['lat'],
                    'delivery_lng' => $coords['lng'],
                    'delivery_source' => 'geocoded',
                ]);

                return $coords;
            }
        }

        return null;
    }

    /**
     * Save the customer's device position (from "Use my location" during
     * checkout) as the delivery point. It is more precise than geocoding a
     * typed address such as "Kawempe, Kampala".
     */
    public function rememberCustomerPosition(OrderTracking $tracking, float $lat, float $lng): void
    {
        $tracking->update([
            'delivery_lat' => $lat,
            'delivery_lng' => $lng,
            'delivery_source' => 'gps',
        ]);
    }

    /**
     * Address strings to try, most specific first. Saved addresses are
     * often partial ("kawempe" / "kisaasi"), so fall back to each part.
     */
    protected function addressCandidates($address): array
    {
        $line1 = trim((string)($address->address_1 ?? ''));
        $line2 = trim((string)($address->address_2 ?? ''));
        $city = trim((string)($address->city ?? ''));

        $candidates = [
            implode(', ', array_filter([$line1, $line2, $city])),
            implode(', ', array_filter([$line1, $city])),
            implode(', ', array_filter([$line2, $city])),
            $line1,
            $city,
        ];

        return array_values(array_unique(array_filter($candidates, fn($value) => strlen($value) > 2)));
    }

    protected function geocode(string $text): ?array
    {
        try {
            $place = app('geocoder')->geocode($text.', Uganda')->first();
        } catch (Throwable $e) {
            Log::warning('Delivery geocoding failed', ['address' => $text, 'error' => $e->getMessage()]);

            return null;
        }

        $coordinates = $place?->getCoordinates();
        if (!$coordinates || !$coordinates->getLatitude() || !$coordinates->getLongitude()) {
            return null;
        }

        return ['lat' => (float)$coordinates->getLatitude(), 'lng' => (float)$coordinates->getLongitude()];
    }
}
