<?php
/**
 * Deep Laravel debug - simulates index.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Laravel Deep Debug</h1>";
echo "<pre>";

try {
    echo "Step 1: Loading autoload...\n";
    require __DIR__.'/../vendor/autoload.php';
    echo "OK\n";
    
    echo "\nStep 2: Loading bootstrap/app.php...\n";
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "OK - App class: " . get_class($app) . "\n";
    
    echo "\nStep 3: Making HTTP Kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "OK - Kernel class: " . get_class($kernel) . "\n";
    
    echo "\nStep 4: Capturing request...\n";
    $request = Illuminate\Http\Request::capture();
    echo "OK - Request URI: " . $request->getRequestUri() . "\n";
    
    echo "\nStep 5: Handling request (this bootstraps everything)...\n";
    $response = $kernel->handle($request);
    echo "OK - Response status: " . $response->getStatusCode() . "\n";
    
    echo "\nStep 6: Checking config after handle...\n";
    echo "app.name: " . config('app.name') . "\n";
    echo "app.env: " . config('app.env') . "\n";
    
    echo "\n=== SUCCESS - Laravel is working! ===\n";
    echo "\nThe actual response would be:\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content length: " . strlen($response->getContent()) . " bytes\n";
    
} catch (Throwable $e) {
    echo "\n\nERROR at step above!\n";
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
