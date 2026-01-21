<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test geocoding
try {
    echo "Testing Nominatim geocoding for 'Kawempe'...\n";
    
    $geocoder = app('geocoder');
    $results = $geocoder->geocode('Kawempe');
    
    echo "Success! Found " . $results->count() . " results:\n";
    foreach ($results as $result) {
        echo "  - " . $result->getStreetName() . ", " . $result->getLocality() . "\n";
        $coords = $result->getCoordinates();
        echo "    Coordinates: " . $coords->getLatitude() . ", " . $coords->getLongitude() . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
