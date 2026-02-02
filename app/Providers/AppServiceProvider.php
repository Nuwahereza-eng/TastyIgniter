<?php

namespace App\Providers;

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
        //
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
        
        // Force demo theme in production
        if (config('app.env') === 'production') {
            $this->app->booted(function () {
                try {
                    // Force set the active theme to demo
                    if (class_exists(\Igniter\Main\Classes\ThemeManager::class)) {
                        $themeManager = resolve(\Igniter\Main\Classes\ThemeManager::class);
                        $themeManager->setActiveTheme('demo');
                    }
                } catch (\Throwable $e) {
                    // Log but don't crash
                    logger()->warning('Failed to set demo theme: ' . $e->getMessage());
                }
            });
        }
    }
}
