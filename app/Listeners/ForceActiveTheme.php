<?php

namespace App\Listeners;

/**
 * Forces the demo theme to be active.
 * 
 * This listener responds to TastyIgniter's ThemeGetActiveEvent
 * to override the active theme and ensure demo theme is always used.
 */
class ForceActiveTheme
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return string|null
     */
    public function handle($event = null)
    {
        return 'demo';
    }
}
