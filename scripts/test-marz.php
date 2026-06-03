<?php
// scripts/test-marz.php
use App\Services\PaymentService;

$svc = new PaymentService();
$result = $svc->initializeMarzPayment([
    'amount' => 1000,
    'country' => 'UG',
    'method' => 'card',
    'description' => 'Test card collection from UgaEats',
    'redirect_url' => 'https://example.com/payment/callback',
]);

echo "--- initializeMarzPayment (card) ---\n";
print_r($result);
