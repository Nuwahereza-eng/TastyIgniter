<?php
// scripts/register-marz-webhook.php
// Usage:
//   MARZ_WEBHOOK_URL=https://yourdomain.com/payment/marz-webhook \
//   MARZ_ENV=production \
//   php -r "require 'vendor/autoload.php'; \$app=require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); require 'scripts/register-marz-webhook.php';"

use Illuminate\Support\Facades\Http;

$cred = env('MARZ_PUBLIC_KEY');
$base = rtrim(env('MARZ_BASE_URL', 'https://wallet.wearemarz.com/api/v1'), '/');
$url = getenv('MARZ_WEBHOOK_URL') ?: 'https://YOUR-DOMAIN.com/payment/marz-webhook';
$env = getenv('MARZ_ENV') ?: 'production'; // production | sandbox | test

if (str_contains($url, 'YOUR-DOMAIN')) {
    fwrite(STDERR, "Set MARZ_WEBHOOK_URL env var to your public webhook URL.\n");
    exit(1);
}

$events = [
    'collection.completed',
    'collection.failed',
];

foreach ($events as $event) {
    $payload = [
        'name' => 'UgaEats - ' . $event,
        'url' => $url,
        'event_type' => $event,
        'environment' => $env,
    ];

    $r = Http::withHeaders([
        'Authorization' => 'Basic ' . $cred,
        'Accept' => 'application/json',
    ])->asJson()->post($base . '/webhooks', $payload);

    echo "[$event] HTTP {$r->status()}\n";
    echo $r->body() . "\n\n";
}

// List webhooks after creation
$list = Http::withHeaders(['Authorization' => 'Basic ' . $cred, 'Accept' => 'application/json'])
    ->get($base . '/webhooks');
echo "--- All webhooks ---\n";
echo $list->body() . "\n";
