<?php
/**
 * Debug endpoint for Railway deployment
 * Access at /debug.php to see configuration status
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>TastyIgniter Debug Info</h1>";
echo "<pre>";

// PHP Info
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n\n";

// Environment Variables
echo "=== Environment Variables ===\n";
$envVars = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'APP_KEY', 'APP_URL', 'APP_ENV', 'APP_DEBUG'];
foreach ($envVars as $var) {
    $value = getenv($var) ?: ($_ENV[$var] ?? $_SERVER[$var] ?? 'NOT SET');
    if ($var === 'DB_PASSWORD' || $var === 'APP_KEY') {
        $value = $value !== 'NOT SET' ? '***SET***' : 'NOT SET';
    }
    echo "$var: $value\n";
}

// Check .env file
echo "\n=== .env File ===\n";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo ".env exists: YES\n";
    echo "Size: " . filesize($envFile) . " bytes\n";
} else {
    echo ".env exists: NO\n";
}

// Check storage directories
echo "\n=== Storage Directories ===\n";
$dirs = [
    '../storage/framework/sessions',
    '../storage/framework/views', 
    '../storage/framework/cache',
    '../storage/logs',
    '../bootstrap/cache'
];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    $exists = is_dir($path) ? 'YES' : 'NO';
    $writable = is_writable($path) ? 'YES' : 'NO';
    echo "$dir - Exists: $exists, Writable: $writable\n";
}

// Test database connection
echo "\n=== Database Connection ===\n";
try {
    $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost');
    $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306');
    $db = getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'tastyigniter');
    $user = getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'root');
    $pass = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');
    
    echo "Attempting connection to: $host:$port/$db\n";
    
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db",
        $user,
        $pass,
        [PDO::ATTR_TIMEOUT => 5]
    );
    echo "Connection: SUCCESS\n";
    
    // Check maintenance mode
    $stmt = $pdo->query("SELECT value FROM ti_settings WHERE item = 'maintenance_mode'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Maintenance Mode: " . ($row['value'] ?? 'unknown') . "\n";
    
} catch (Exception $e) {
    echo "Connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

// Try to bootstrap Laravel
echo "\n=== Laravel Bootstrap ===\n";
try {
    require __DIR__.'/../vendor/autoload.php';
    echo "Autoload: OK\n";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "App bootstrap: OK\n";
    
    // Try to actually run the kernel
    echo "\n=== Testing Laravel Request ===\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel created: OK\n";
    
    // Check config values
    echo "\n=== Laravel Config ===\n";
    echo "app.debug: " . (config('app.debug') ? 'true' : 'false') . "\n";
    echo "app.env: " . config('app.env') . "\n";
    echo "app.url: " . config('app.url') . "\n";
    echo "database.default: " . config('database.default') . "\n";
    
    // Test database through Laravel
    echo "\n=== Laravel Database ===\n";
    $dbConfig = config('database.connections.mysql');
    echo "DB host from config: " . ($dbConfig['host'] ?? 'not set') . "\n";
    echo "DB name from config: " . ($dbConfig['database'] ?? 'not set') . "\n";
    
    // Try a simple query through Laravel
    try {
        $result = \DB::select('SELECT 1 as test');
        echo "Laravel DB query: OK\n";
    } catch (Exception $dbEx) {
        echo "Laravel DB query FAILED: " . $dbEx->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Laravel Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
