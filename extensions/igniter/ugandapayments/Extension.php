<?php

namespace Igniter\UgandaPayments;

use Igniter\Flame\Filesystem\Filesystem;
use Igniter\System\Classes\BaseExtension;

/**
 * Uganda Payment Methods Extension
 */
class Extension extends BaseExtension
{
    public function register()
    {
        // Ensure path symbol is registered early for config file resolution
        $this->app->afterResolving(Filesystem::class, function (Filesystem $files) {
            $resourcesPath = __DIR__ . '/resources';
            if (is_dir($resourcesPath)) {
                $files->addPathSymbol('igniter.ugandapayments', $resourcesPath);
            }
        });

        if ($this->app->resolved(Filesystem::class)) {
            $files = $this->app->make(Filesystem::class);
            $resourcesPath = __DIR__ . '/resources';
            if (is_dir($resourcesPath)) {
                $files->addPathSymbol('igniter.ugandapayments', $resourcesPath);
            }
        }
    }

    public function boot()
    {
        // Register views from resources folder
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'igniter.ugandapayments');
    }

    public function registerPaymentGateways(): array
    {
        return [
            \Igniter\UgandaPayments\Payments\MobileMoney::class => [
                'code' => 'mobilemoney',
                'name' => 'Mobile Money (MTN & Airtel)',
                'description' => 'Pay using MTN Mobile Money or Airtel Money',
            ],
            \Igniter\UgandaPayments\Payments\Flutterwave::class => [
                'code' => 'flutterwave',
                'name' => 'Flutterwave',
                'description' => 'Pay using Flutterwave payment gateway (Cards, Mobile Money)',
            ],
            \Igniter\UgandaPayments\Payments\TastyWalletGateway::class => [
                'code' => 'tastywallet',
                'name' => 'Tasty Wallet',
                'description' => 'Pay using your Tasty Wallet balance - get 5% cashback!',
            ],
            \Igniter\UgandaPayments\Payments\Marz::class => [
                'code' => 'marz',
                'name' => 'Marz Wallet',
                'description' => 'Pay using Marz Wallet (https://wallet.wearemarz.com)',
            ],
        ];
    }
}
