<?php

declare(strict_types=1);

namespace App\Helpers;

use Igniter\Main\Classes\MediaLibrary;
use Illuminate\Support\Facades\Log;

/**
 * Safe ImageHelper that catches exceptions during thumbnail generation
 */
class SafeImageHelper
{
    public static function resize(string $path, int|array $width = 0, int $height = 0): string
    {
        try {
            $options = array_merge([
                'width' => is_array($width) ? 0 : $width,
                'height' => $height,
            ], is_array($width) ? $width : []);

            $rootFolder = config('igniter-system.assets.media.folder', 'data').'/';
            if (str_starts_with($path, $rootFolder)) {
                $path = substr($path, strlen($rootFolder));
            }

            return resolve(MediaLibrary::class)->getMediaThumb($path, $options);
        } catch (\Exception $e) {
            Log::warning('SafeImageHelper::resize failed: ' . $e->getMessage() . ' for path: ' . $path);
            
            // Try to return original media URL
            try {
                return media_url($path);
            } catch (\Exception $e2) {
                // Last resort fallback
                return asset('vendor/igniter-orange/images/favicon.ico');
            }
        }
    }
}
