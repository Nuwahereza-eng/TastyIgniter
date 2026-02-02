<?php

/**
 * ULTRA NUCLEAR HELPERS - Self-contained, no dependencies
 * 
 * These functions are defined BEFORE vendor helpers are loaded.
 * They must be completely self-contained with NO class dependencies
 * because classes aren't autoloaded yet when this file runs.
 * 
 * TastyIgniter helpers use `if (!function_exists(...))` pattern,
 * so our versions take precedence.
 */

// Define safe placeholder constant
if (!defined('SAFE_IMAGE_PLACEHOLDER')) {
    define('SAFE_IMAGE_PLACEHOLDER', '/vendor/igniter-orange/images/favicon.ico');
}

if (!function_exists('media_thumb')) {
    /**
     * Safe media thumbnail - returns placeholder, no Glide processing
     * This completely bypasses the Glide image processor that crashes
     */
    function media_thumb(?string $path, array $options = []): string
    {
        // If path is empty, return placeholder
        if (empty($path)) {
            return SAFE_IMAGE_PLACEHOLDER;
        }
        
        // If it's already a full URL, return it
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // Try to return as media URL without Glide processing
        // Build a simple path to the media file
        $mediaPath = '/storage/app/public/' . ltrim($path, '/');
        if (file_exists(dirname(__DIR__) . '/public' . $mediaPath)) {
            return $mediaPath;
        }
        
        // Fallback to placeholder
        return SAFE_IMAGE_PLACEHOLDER;
    }
}

if (!function_exists('resize')) {
    /**
     * Safe resize - returns original URL, no Glide processing
     */
    function resize($url = null, $width = 0, $height = 0, $options = [])
    {
        if (empty($url)) {
            return SAFE_IMAGE_PLACEHOLDER;
        }
        return $url;
    }
}
