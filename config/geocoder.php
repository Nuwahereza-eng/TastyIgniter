<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Default Provider name
    |---------------------------------------------------------------------------
    |
    | The `chain` provider is special, in that it will run all configured
    | providers in the sequence listed, should the previous provider fail.
    | Using nominatim until Google Geocoding API is enabled in Cloud Console.
    | Set GEOCODER_PROVIDER=google in .env once you enable the Google Geocoding API.
    |
    */
    'default' => env('GEOCODER_PROVIDER', 'nominatim'),

    /*
    |---------------------------------------------------------------------------
    | Providers
    |---------------------------------------------------------------------------
    |
    | Uganda-specific geocoding configuration
    |
    */

    'providers' => [
        'google' => [
            'endpoints' => [
                'geocode' => 'https://maps.googleapis.com/maps/api/geocode/json?address=%s',
                'reverse' => 'https://maps.googleapis.com/maps/api/geocode/json?latlng=%F,%F',
                'distance' => 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations=%F,%F&origins=%F,%F',
                'places' => 'https://places.googleapis.com/v1/places',
            ],
            'locale' => 'en-UG',
            'region' => 'UG',
            'apiKey' => env('GOOGLE_MAPS_API_KEY', null),
        ],
        'nominatim' => [
            'endpoints' => [
                // Add countrycodes=UG to limit results to Uganda for faster responses
                'geocode' => 'https://nominatim.openstreetmap.org/search?q=%s&format=json&addressdetails=1&limit=%d&countrycodes=UG',
                'reverse' => 'https://nominatim.openstreetmap.org/reverse?format=json&lat=%F&lon=%F&addressdetails=1&zoom=%d',
                'distance' => 'https://routing.openstreetmap.de/routed-car/route/v1/driving/%F,%F;%F,%F',
                'places' => 'https://nominatim.openstreetmap.org/',
            ],
            'locale' => 'en-UG',
            'region' => 'UG', // Uganda country code
            'userAgent' => 'UgaEats-TastyIgniter/1.0 (contact@ugaeats.com)',
            'referer' => env('APP_URL', 'http://127.0.0.1:8000'),
            // Increase timeout for slower connections
            'timeout' => 30,
            'connectTimeout' => 15,
        ],
    ],

    'cache' => [
        'store' => null,
        'duration' => 86400, // 24 hours - cache results to reduce API calls
    ],

    'precision' => 8,
];
