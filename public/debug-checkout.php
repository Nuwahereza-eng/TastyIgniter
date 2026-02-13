<?php
// Debug script to test checkout flow
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Checkout Debug</h2>";

// Check session cart
$cart = app('cart');
echo "<h3>Cart:</h3>";
echo "Items: " . $cart->count() . "<br>";
echo "Total: " . $cart->total() . "<br>";

// Check location
$locationFacade = app(\Igniter\Local\Facades\Location::class);
echo "<h3>Location:</h3>";
$current = $locationFacade->current();
echo "Current: " . ($current ? $current->getName() : 'None') . "<br>";

// Check logged in user
$customer = \Igniter\User\Facades\Auth::customer();
echo "<h3>Customer:</h3>";
echo "Logged in: " . ($customer ? 'Yes - ' . $customer->email : 'No') . "<br>";

// Check payment methods
echo "<h3>Payment Methods:</h3>";
$payments = \Igniter\PayRegister\Models\Payment::where('status', 1)->get();
foreach ($payments as $p) {
    echo $p->code . " - " . $p->name . "<br>";
}

// Check if there's an order in session
echo "<h3>Session Order:</h3>";
$orderHash = session('order_hash');
echo "Order hash: " . ($orderHash ?: 'None') . "<br>";
