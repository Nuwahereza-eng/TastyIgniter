<?php
// scripts/register-marz-payment.php
use Illuminate\Support\Facades\DB;

$existing = DB::table('payments')->where('code', 'marz')->first();

$data = json_encode([
    'test_mode' => true,
    'currency' => 'UGX',
    'order_total' => 1000,
    'order_status' => 1,
    'base_url' => 'https://wallet.wearemarz.com/api',
]);

if (!$existing) {
    DB::table('payments')->insert([
        'name' => 'Marz Wallet',
        'code' => 'marz',
        'class_name' => 'Igniter\\UgandaPayments\\Payments\\Marz',
        'description' => 'Pay using Marz Wallet (https://wallet.wearemarz.com)',
        'data' => $data,
        'status' => 1,
        'is_default' => 0,
        'priority' => 10,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Inserted marz payment row\n";
} else {
    DB::table('payments')->where('code', 'marz')->update([
        'status' => 1,
        'class_name' => 'Igniter\\UgandaPayments\\Payments\\Marz',
        'updated_at' => now(),
    ]);
    echo "Updated existing marz row (id={$existing->payment_id})\n";
}
