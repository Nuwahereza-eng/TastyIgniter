<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Force demo theme via config (earliest possible override)
        config(['igniter-system.defaultTheme' => 'demo']);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS in production (Railway)
        if (config('app.env') === 'production' || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }
        
        // Register event listener to force demo theme
        // This intercepts the ThemeGetActiveEvent and returns 'demo'
        if (class_exists(\Igniter\Main\Events\ThemeGetActiveEvent::class)) {
            Event::listen(\Igniter\Main\Events\ThemeGetActiveEvent::class, function ($event) {
                return 'demo';
            });
        }
        
        // Checkout pre-fills "Address 1" as "<street number> <street name>"
        // from the customer's detected location. Many Kampala locations have
        // neither, which saved " " and then blocked every admin edit of that
        // customer ("The Address 1 field is required."). Fall back to the area.
        if (class_exists(\Igniter\User\Models\Address::class)) {
            \Igniter\User\Models\Address::saving(function ($address) {
                if (strlen(trim((string)$address->address_1)) >= 3) {
                    $address->address_1 = trim($address->address_1);
                    return;
                }

                $address->address_1 = collect([$address->address_2, $address->city, $address->state])
                    ->map(fn ($part) => trim((string)$part))
                    ->first(fn ($part) => strlen($part) >= 3) ?? 'Pinned location';
            });
        }

        // Also try to set via ThemeManager after app boots
        $this->app->booted(function () {
            try {
                if (class_exists(\Igniter\Main\Classes\ThemeManager::class)) {
                    $themeManager = resolve(\Igniter\Main\Classes\ThemeManager::class);
                    if (method_exists($themeManager, 'setActiveTheme')) {
                        $themeManager->setActiveTheme('demo');
                    }
                }
            } catch (\Throwable $e) {
                logger()->warning('Failed to set demo theme: ' . $e->getMessage());
            }
        });
    }
}
