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
