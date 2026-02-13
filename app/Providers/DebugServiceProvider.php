<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class DebugServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Log all Livewire errors
        Event::listen('livewire:*', function ($eventName, $data) {
            if (str_contains($eventName, 'exception') || str_contains($eventName, 'error')) {
                Log::error('Livewire event: ' . $eventName, ['data' => $data]);
            }
        });

        // Log validation exceptions
        Event::listen(\Illuminate\Validation\ValidationException::class, function ($exception) {
            Log::warning('Validation failed', [
                'errors' => $exception->errors(),
            ]);
        });
    }
}
