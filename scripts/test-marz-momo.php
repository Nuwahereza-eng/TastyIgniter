<?php
// scripts/test-marz-momo.php
// Usage: edit $phone below to YOUR MTN/Airtel number, then run:
//   php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); require 'scripts/test-marz-momo.php';"
use App\Services\PaymentService;

$phone = getenv('MARZ_TEST_PHONE') ?: '+256700000000';

$svc = new PaymentService();
$result = $svc->initializeMarzPayment([
    'amount' => 500,
    'country' => 'UG',
    'method' => 'mobile_money',
    'phone' => $phone,
    'description' => 'UgaEats mobile money test',
]);

echo "--- initializeMarzPayment (mobile_money to $phone) ---\n";
print_r($result);
