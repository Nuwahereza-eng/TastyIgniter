<?php

namespace App\Geolite;

use Igniter\Flame\Geolite\Contracts\GeoQueryInterface;
use Igniter\Flame\Geolite\Exceptions\GeoliteException;
use Igniter\Flame\Geolite\Place;
use Igniter\Flame\Geolite\Provider\NominatimProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomNominatimProvider extends NominatimProvider
{
    /**
     * Override placesAutocomplete to cast place_id to string and add User-Agent
     * with improved timeout handling for Uganda
     */
    public function placesAutocomplete(GeoQueryInterface $query): Collection
    {
        $endpoint = array_get($this->config, 'endpoints.places');
        
        // Add countrycodes=UG to restrict to Uganda for faster results
        $url = sprintf($endpoint.'search?q=%s&format=json&addressdetails=1&limit=%d&countrycodes=UG',
            rawurlencode($query->getText()),
            $query->getLimit(),
        );

        try {
            $result = $this->cacheCallback($url, fn(): array => $this->requestPlacesUrl($url, $query));

            return collect($result)->map(fn($item) => (new Place)
                ->placeId((string)$item->place_id) // Cast to string
                ->title($item->name ?? $item->display_name ?? 'Unknown')
                ->description($item->display_name ?? '')
                ->provider('nominatim')
                ->withData('osmType', $item->osm_type ?? null)
                ->withData('osmId', $item->osm_id ?? null)
                ->withData('class', $item->category ?? $item->class ?? null)
                ->withData('latitude', (float)($item->lat ?? 0))
                ->withData('longitude', (float)($item->lon ?? 0)));
        } catch (Throwable $throwable) {
            // Log the error but return empty collection instead of throwing
            Log::warning(sprintf(
                'Nominatim geocoding failed: %s. Query: %s',
                $throwable->getMessage(),
                $query->getText()
            ));

            // Return empty collection instead of crashing
            return collect([]);
        }
    }

    /**
     * Override the requestPlacesUrl method to include User-Agent header
     * with increased timeout for slower connections
     */
    protected function requestPlacesUrl(string $url, GeoQueryInterface $query): array
    {
        // Uganda country code already added in placesAutocomplete
        
        $options = [
            'headers' => [
                'User-Agent' => 'UgaEats-TastyIgniter/1.0 (contact@ugaeats.com)',
                'Referer' => config('app.url', 'http://127.0.0.1:8000'),
                'Accept-Language' => 'en-UG,en;q=0.9',
            ],
            'timeout' => 30, // Increased timeout
            'connect_timeout' => 15, // Connection timeout
        ];

        try {
            $response = $this->getHttpClient()->get($url, $options);
            return $this->parseResponse($response);
        } catch (Throwable $e) {
            Log::error('Nominatim request failed: ' . $e->getMessage());
            return [];
        }
    }
}
