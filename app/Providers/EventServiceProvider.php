<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Wire the Igniter Cart lifecycle events into our local track-order
        // subsystem so a tracking row is created on payment and kept in sync
        // when an admin advances the order status.
        Event::listen('admin.order.paymentProcessed', [\App\Listeners\SyncOrderTracking::class, 'onPaymentProcessed']);
        Event::listen('igniter.cart.orderStatusAdded', [\App\Listeners\SyncOrderTracking::class, 'onStatusAdded']);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
