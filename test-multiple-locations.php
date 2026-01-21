<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$locations = ['Kampala', 'Entebbe', 'Nakawa', 'Makindye', 'Jinja'];

foreach ($locations as $location) {
    try {
        echo "\n🔍 Testing: $location\n";
        
        $geocoder = app('geocoder');
        $results = $geocoder->geocode($location);
        
        echo "✅ Found " . $results->count() . " results\n";
        if ($results->count() > 0) {
            $first = $results->first();
            $coords = $first->getCoordinates();
            echo "   📍 {$first->getLocality()}: " . $coords->getLatitude() . ", " . $coords->getLongitude() . "\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    // Respect Nominatim rate limit (1 request per second)
    sleep(1);
}

echo "\n✨ All tests completed!\n";
