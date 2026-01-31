<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'ajax/location-schedule/*',
        'ajax/group-orders/*',
        'ajax/group-orders',
        'ajax/tracking/*',
        'ajax/tracking',
        'ajax/orders/*',
        'ajax/orders',
        'api/*',
    ];
}
