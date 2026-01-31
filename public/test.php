<?php
/**
 * Simple test that bypasses Laravel completely
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simple PHP Test (No Laravel)</h1>";
echo "<pre>";

// Check .env file
echo "=== .env File ===\n";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo ".env exists: YES\n";
    echo "Contents:\n";
    $content = file_get_contents($envFile);
    // Hide password
    $content = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=***HIDDEN***', $content);
    echo $content . "\n";
} else {
    echo ".env exists: NO\n";
}

// Check bootstrap cache files
echo "\n=== Bootstrap Cache Files ===\n";
$cacheFiles = [
    'packages.php',
    'services.php', 
    'config.php',
    'addons.php',
    'classes.php'
];
foreach ($cacheFiles as $file) {
    $path = __DIR__ . '/../bootstrap/cache/' . $file;
    $exists = file_exists($path) ? 'YES (' . filesize($path) . ' bytes)' : 'NO';
    echo "$file: $exists\n";
}

// Direct database test
echo "\n=== Direct Database Test ===\n";
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $db = getenv('DB_DATABASE') ?: 'tastyigniter';
    $user = getenv('DB_USERNAME') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    echo "Database: CONNECTED\n";
    
    // Count tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "\n";
    
    // Check for key TastyIgniter tables
    $keyTables = ['ti_users', 'ti_orders', 'ti_locations', 'ti_settings'];
    foreach ($keyTables as $table) {
        $exists = in_array($table, $tables) ? 'YES' : 'NO';
        echo "  $table: $exists\n";
    }
    
} catch (Exception $e) {
    echo "Database ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== PHP Extensions ===\n";
$required = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl', 'gd'];
foreach ($required as $ext) {
    $loaded = extension_loaded($ext) ? 'YES' : 'NO';
    echo "$ext: $loaded\n";
}

echo "</pre>";
