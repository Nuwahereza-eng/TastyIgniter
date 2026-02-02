<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

// CRITICAL FIX: Define safe media_thumb BEFORE vendor autoloader
// The vendor helpers.php uses "if (!function_exists('media_thumb'))" 
// so our version takes precedence and prevents the crash
if (!function_exists('media_thumb')) {
    function media_thumb(?string $path, array $options = []): string
    {
        // After autoload, try the real implementation with error handling
        try {
            if (class_exists('Igniter\Main\Classes\MediaLibrary')) {
                $library = app('Igniter\Main\Classes\MediaLibrary');
                return $library->getMediaThumb($path ?? 'no_photo.png', $options);
            }
        } catch (\Throwable $e) {
            // Log error if logging is available
            if (function_exists('logger')) {
                logger()->warning('media_thumb failed: ' . $e->getMessage());
            }
        }
        
        // Fallback: try media_url
        try {
            if (function_exists('media_url')) {
                return media_url($path ?? 'no_photo.png');
            }
        } catch (\Throwable $e) {
            // Ignore
        }
        
        // Ultimate fallback: static asset
        return '/vendor/igniter-orange/images/favicon.ico';
    }
}

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
