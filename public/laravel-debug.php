<?php
/**
 * Deep Laravel debug
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
    
    echo "\nStep 3: Checking if config is bound...\n";
    echo "Config bound: " . ($app->bound('config') ? 'YES' : 'NO') . "\n";
    
    echo "\nStep 4: Checking registered providers...\n";
    $providers = $app->getLoadedProviders();
    echo "Loaded providers: " . count($providers) . "\n";
    foreach (array_slice(array_keys($providers), 0, 10) as $p) {
        echo "  - $p\n";
    }
    
    echo "\nStep 5: Trying to manually register ConfigServiceProvider...\n";
    if (!$app->bound('config')) {
        $app->register(\Illuminate\Config\ConfigServiceProvider::class);
        echo "Registered ConfigServiceProvider\n";
    }
    echo "Config bound now: " . ($app->bound('config') ? 'YES' : 'NO') . "\n";
    
    echo "\nStep 6: Getting HTTP Kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "OK - Kernel class: " . get_class($kernel) . "\n";
    
    echo "\nStep 7: Bootstrapping the app...\n";
    $app->bootstrapWith([
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ]);
    echo "OK - App bootstrapped\n";
    
    echo "\nStep 8: Checking config values...\n";
    echo "app.name: " . config('app.name') . "\n";
    echo "app.env: " . config('app.env') . "\n";
    echo "app.url: " . config('app.url') . "\n";
    
    echo "\nStep 9: Testing database...\n";
    $result = \DB::select('SELECT 1 as test');
    echo "Database query: OK\n";
    
    echo "\n=== SUCCESS - Laravel is working! ===\n";
    
} catch (Throwable $e) {
    echo "\n\nERROR at step above!\n";
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
