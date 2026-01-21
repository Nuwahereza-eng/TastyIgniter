<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Default Provider name
    |---------------------------------------------------------------------------
    |
    | The `chain` provider is special, in that it will run all configured
    | providers in the sequence listed, should the previous provider fail.
    |
    */
    'default' => 'nominatim',

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
                'geocode' => 'https://nominatim.openstreetmap.org/search?q=%s&format=json&addressdetails=1&limit=%d',
                'reverse' => 'https://nominatim.openstreetmap.org/reverse?format=json&lat=%F&lon=%F&addressdetails=1&zoom=%d',
                'distance' => 'https://routing.openstreetmap.de/routed-car/route/v1/driving/%F,%F;%F,%F',
                'places' => 'https://nominatim.openstreetmap.org/',
            ],
            'locale' => 'en-UG',
            'region' => 'UG', // Uganda country code
            'userAgent' => 'TastyIgniter-Uganda/1.0 (+http://localhost:8001)',
            'referer' => 'http://localhost:8001',
        ],
    ],

    'cache' => [
        'store' => null,
        'duration' => 43200, // 30 days
    ],

    'precision' => 8,
];
