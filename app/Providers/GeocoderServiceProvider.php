<?php

namespace App\Providers;

use App\Geolite\CustomNominatimProvider;
use Igniter\Flame\Geolite\Contracts\GeoQueryInterface;
use Illuminate\Support\ServiceProvider;

class GeocoderServiceProvider extends ServiceProvider
{
    /**
     * Greater Kampala bounding box (west, north, east, south) used to bias
     * OpenStreetMap searches. Results outside it are still returned.
     */
    public const KAMPALA_VIEWBOX = '32.40,0.50,32.80,0.15';

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // TastyIgniter's System ServiceProvider also writes to this config
        // namespace from the admin settings table, so we re-apply here on
        // resolve to guarantee these values win.
        $this->app->resolving('geocoder', function ($geocoder, $app) {
            $provider = env('GEOCODER_PROVIDER', 'nominatim');
            $apiKey = env('GOOGLE_MAPS_API_KEY') ?: setting('maps_api_key');

            // Google is only usable with a key on a billing-enabled project.
            // Run it through the chain provider so that when Google refuses a
            // request (e.g. "You must enable Billing") OpenStreetMap answers.
            if ($provider === 'google') {
                $provider = $apiKey ? 'chain' : 'nominatim';
            }

            $app['config']->set('igniter-geocoder.default', $provider);
            if ($apiKey) {
                $app['config']->set('igniter-geocoder.providers.google.apiKey', $apiKey);
            }
            $app['config']->set('igniter-geocoder.providers.google.region', 'UG');
            $app['config']->set('igniter-geocoder.providers.google.locale', 'en-UG');

            // Restrict OpenStreetMap results to Uganda and rank results inside
            // greater Kampala first, otherwise "Kampala Road" resolves to the
            // street of that name in Mityana.
            $viewbox = '&countrycodes=ug&viewbox='.self::KAMPALA_VIEWBOX;
            $app['config']->set('igniter-geocoder.providers.nominatim.endpoints.geocode',
                'https://nominatim.openstreetmap.org/search?q=%s&format=json&addressdetails=1&limit=%d'.$viewbox);
            $app['config']->set('igniter-geocoder.providers.nominatim.region', 'UG');
            $app['config']->set('igniter-geocoder.providers.nominatim.locale', 'en');

            // Short cache duration so wrong / stale reverse-geocodes can't
            // linger after the user moves or after we switch providers.
            $app['config']->set('igniter-geocoder.cache.duration', 300);

            // Keep the custom Nominatim provider available as a fallback
            // (e.g. when using the chain provider in dev).
            $geocoder->extend('nominatim', function ($app) {
                return new CustomNominatimProvider(
                    new \GuzzleHttp\Client(),
                    $app['config']->get('igniter-geocoder.providers.nominatim', [])
                );
            });
        });
        
        // Intercept geocoder calls to inject User-Agent
        $this->app->extend('geocoder', function ($geocoder, $app) {
            $userAgent = config('app.name', 'TastyIgniter-Uganda') . '/1.0 (+' . config('app.url', 'http://localhost') . ')';
            
            return new class($geocoder, $userAgent) {
                private $geocoder;
                private $userAgent;
                
                public function __construct($geocoder, $userAgent)
                {
                    $this->geocoder = $geocoder;
                    $this->userAgent = $userAgent;
                }
                
                private function injectUserAgent(GeoQueryInterface $query): GeoQueryInterface
                {
                    // Inject User-Agent into query data if not already set
                    if (!$query->getData('userAgent')) {
                        $query = $query->withData('userAgent', $this->userAgent);
                    }
                    
                    return $query;
                }
                
                public function geocodeQuery(GeoQueryInterface $query)
                {
                    return $this->geocoder->geocodeQuery($this->injectUserAgent($query));
                }
                
                public function reverseQuery(GeoQueryInterface $query)
                {
                    return $this->geocoder->reverseQuery($this->injectUserAgent($query));
                }
                
                public function driver($driver = null)
                {
                    $actualDriver = $this->geocoder->driver($driver);
                    $userAgent = $this->userAgent;
                    
                    // Wrap the driver to inject User-Agent
                    return new class($actualDriver, $userAgent) {
                        private $driver;
                        private $userAgent;
                        
                        public function __construct($driver, $userAgent)
                        {
                            $this->driver = $driver;
                            $this->userAgent = $userAgent;
                        }
                        
                        private function injectUserAgent(GeoQueryInterface $query): GeoQueryInterface
                        {
                            if (!$query->getData('userAgent')) {
                                $query = $query->withData('userAgent', $this->userAgent);
                            }
                            return $query;
                        }
                        
                        public function geocodeQuery(GeoQueryInterface $query)
                        {
                            return $this->driver->geocodeQuery($this->injectUserAgent($query));
                        }
                        
                        public function reverseQuery(GeoQueryInterface $query)
                        {
                            return $this->driver->reverseQuery($this->injectUserAgent($query));
                        }
                        
                        public function placesAutocomplete(GeoQueryInterface $query)
                        {
                            return $this->driver->placesAutocomplete($this->injectUserAgent($query));
                        }
                        
                        public function __call($method, $parameters)
                        {
                            return $this->driver->$method(...$parameters);
                        }
                    };
                }
                
                public function __call($method, $parameters)
                {
                    return $this->geocoder->$method(...$parameters);
                }
            };
        });
    }
}
