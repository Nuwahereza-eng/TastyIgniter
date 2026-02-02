<?php

/**
 * Custom helpers that override TastyIgniter core helpers
 * This file is autoloaded BEFORE vendor helpers via composer.json
 * 
 * The key is that TastyIgniter helpers use `if (!function_exists(...))` pattern,
 * so if we define the function first, our version is used.
 */

use App\Helpers\SafeImageHelper;

if (!function_exists('media_thumb')) {
    /**
     * Safe media thumbnail function that catches exceptions
     * Returns the full thumbnail URL or a fallback if generation fails
     */
    function media_thumb(?string $path, array $options = []): string
    {
        return SafeImageHelper::resize($path ?? 'no_photo.png', $options);
    }
}
