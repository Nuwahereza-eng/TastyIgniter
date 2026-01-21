<?php

namespace App\Geolite;

use Igniter\Flame\Geolite\Contracts\GeoQueryInterface;
use Igniter\Flame\Geolite\Exceptions\GeoliteException;
use Igniter\Flame\Geolite\Place;
use Igniter\Flame\Geolite\Provider\NominatimProvider;
use Illuminate\Support\Collection;
use Throwable;

class CustomNominatimProvider extends NominatimProvider
{
    /**
     * Override placesAutocomplete to cast place_id to string and add User-Agent
     */
    public function placesAutocomplete(GeoQueryInterface $query): Collection
    {
        $endpoint = array_get($this->config, 'endpoints.places');
        $url = sprintf($endpoint.'search?q=%s&format=json&addressdetails=1&limit=%d',
            rawurlencode($query->getText()),
            $query->getLimit(),
        );

        try {
            $result = $this->cacheCallback($url, fn(): array => $this->requestPlacesUrl($url, $query));

            return collect($result)->map(fn($item) => (new Place)
                ->placeId((string)$item->place_id) // Cast to string
                ->title($item->name)
                ->description($item->display_name)
                ->provider('nominatim')
                ->withData('osmType', $item->osm_type)
                ->withData('osmId', $item->osm_id)
                ->withData('class', $item->category ?? null)
                ->withData('latitude', (float)($item->lat ?? 0)) // Cast to float
                ->withData('longitude', (float)($item->lon ?? 0))); // Cast to float
        } catch (Throwable $throwable) {
            $this->log(sprintf(
                'Provider "%s" could not fetch place suggestions, "%s".',
                $this->getName(), $throwable->getMessage(),
            ));

            throw $throwable;
        }
    }

    /**
     * Override the requestPlacesUrl method to include User-Agent header
     */
    protected function requestPlacesUrl(string $url, GeoQueryInterface $query): array
    {
        if ($region = $query->getData('countrycodes', array_get($this->config, 'region'))) {
            $url = sprintf('%s&countrycodes=%s', $url, $region);
        }

        // Fix: Add User-Agent and other headers like the other request methods
        $options['headers']['User-Agent'] = $query->getData('userAgent', request()->userAgent());
        $options['headers']['Referer'] = $query->getData('referer', request()->headers->get('referer'));
        $options['timeout'] = $query->getData('timeout', 15);

        if (empty($options['headers']['User-Agent'])) {
            throw new GeoliteException('The User-Agent must be set to use the Nominatim provider.');
        }

        $response = $this->getHttpClient()->get($url, $options);

        return $this->parseResponse($response);
    }
}
