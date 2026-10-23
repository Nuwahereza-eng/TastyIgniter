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
        Event::listen('admin.assignable.assigned', [\App\Listeners\SyncOrderTracking::class, 'onAssigned']);

        // While a group order is active ("Add items" on the group order page),
        // every cart change is copied to the participant's items right away,
        // so nothing is lost if they skip the "Done Adding" button.
        Event::listen(['cart.added', 'cart.updated', 'cart.removed'], function () {
            if (session()->has('active_group_order_id')) {
                rescue(fn () => app(\App\Services\GroupOrderCartSync::class)->syncActive(), null, false);
            }
        });
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
