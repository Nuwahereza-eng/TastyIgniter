<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test payment gateway loading
echo "<h2>Payment Method Test</h2>";

$payments = \Igniter\PayRegister\Models\Payment::where('status', 1)->get();
echo "<h3>Active Payment Methods:</h3>";
foreach ($payments as $p) {
    echo "<p><strong>{$p->name}</strong> (code: {$p->code})<br>";
    echo "Class: {$p->class_name}<br>";
    
    // Try to instantiate
    try {
        $gateway = $p->getGatewayClass();
        if ($gateway) {
            echo "Gateway loaded: ✓<br>";
        } else {
            echo "Gateway loaded: ✗ (null)<br>";
        }
    } catch (Exception $e) {
        echo "Error loading gateway: " . $e->getMessage() . "<br>";
    }
    echo "</p>";
}

echo "<h3>Test processPaymentForm:</h3>";
try {
    $mobileMoney = \Igniter\PayRegister\Models\Payment::where('code', 'mobilemoney')->first();
    if ($mobileMoney) {
        $gateway = $mobileMoney->getGatewayClass();
        echo "MobileMoney gateway class: " . get_class($gateway) . "<br>";
        
        // Check if method exists
        if (method_exists($gateway, 'processPaymentForm')) {
            $ref = new ReflectionMethod($gateway, 'processPaymentForm');
            echo "processPaymentForm return type: " . ($ref->getReturnType() ?? 'none') . "<br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
