<?php

namespace App\Providers;

use App\Geolite\CustomNominatimProvider;
use Igniter\Flame\Geolite\Contracts\GeoQueryInterface;
use Illuminate\Support\ServiceProvider;

class GeocoderServiceProvider extends ServiceProvider
{
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
        // Register custom Nominatim provider that fixes User-Agent issue
        $this->app->resolving('geocoder', function ($geocoder) {
            $geocoder->extend('nominatim', function ($app) {
                return new CustomNominatimProvider(
                    new \GuzzleHttp\Client(),
                    $app['config']->get('geocoder.providers.nominatim', [])
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
