<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin client for the Sunbird AI translation API.
 *
 * Endpoint: POST {base_url}/tasks/nllb_translate
 *   { "source_language": "eng", "target_language": "lug", "text": "Hello" }
 *
 * Results are cached forever per (target_language, sha1(text)) tuple so we
 * only pay for each unique string once.
 */
class SunbirdTranslator
{
    /** Supported target languages (ISO 639-3 codes used by Sunbird's NLLB model). */
    public const LANGUAGES = [
        'eng' => 'English',
        'lug' => 'Luganda',
        'ach' => 'Acholi',
        'teo' => 'Ateso',
        'lgg' => 'Lugbara',
        'nyn' => 'Runyankole',
    ];

    public function __construct(
        protected ?string $baseUrl = null,
        protected ?string $token = null,
    ) {
        $this->baseUrl = rtrim($this->baseUrl ?? (string) config('services.sunbird.base_url'), '/');
        $this->token = $this->token ?? (string) config('services.sunbird.token');
    }

    public function isConfigured(): bool
    {
        return $this->baseUrl !== '' && $this->token !== '';
    }

    public static function isSupported(string $code): bool
    {
        return array_key_exists($code, self::LANGUAGES);
    }

    /**
     * Translate a single string. Returns the original text when the target is
     * English, when the translator is not configured, or when the API fails.
     */
    public function translate(string $text, string $target, string $source = 'eng'): string
    {
        $text = trim($text);
        if ($text === '' || $target === $source || !self::isSupported($target)) {
            return $text;
        }
        if (!$this->isConfigured()) {
            return $text;
        }

        $key = $this->cacheKey($text, $target, $source);
        $cached = Cache::get($key);
        if ($cached !== null) return $cached;

        $result = $this->call($text, $target, $source);
        // Only cache real translations — never identity fallbacks. That way
        // a transient API failure can't poison the cache.
        if ($result !== $text) {
            Cache::forever($key, $result);
        }
        return $result;
    }

    /**
     * Translate a list of strings. Cached results are returned instantly;
     * any uncached strings are fanned out to Sunbird in parallel using
     * Laravel's Http::pool() so total time ≈ slowest single call (~3s)
     * regardless of how many strings are missing.
     *
     * @param  array<int,string> $texts
     * @return array<string,string>
     */
    public function translateMany(array $texts, string $target, string $source = 'eng'): array
    {
        $out = [];
        $missing = [];

        foreach (array_unique($texts) as $text) {
            $text = (string) $text;
            if (trim($text) === '' || $target === $source || !self::isSupported($target) || !$this->isConfigured()) {
                $out[$text] = $text;
                continue;
            }
            $cached = Cache::get($this->cacheKey($text, $target, $source));
            if ($cached !== null) {
                $out[$text] = $cached;
            } else {
                $missing[] = $text;
            }
        }

        if (empty($missing)) {
            return $out;
        }

        // Fan out missing strings in parallel — but cap concurrency so we
        // don't trigger Sunbird's free-tier rate limits (which respond with
        // 500s and timeouts under burst load).
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $url = "{$this->baseUrl}/tasks/translate";
        $concurrency = 4;
        $chunks = array_chunk($missing, $concurrency);

        foreach ($chunks as $chunk) {
            try {
                $responses = Http::pool(function ($pool) use ($chunk, $headers, $url, $target, $source) {
                    $requests = [];
                    foreach ($chunk as $i => $text) {
                        $requests[] = $pool->as((string) $i)
                            ->withHeaders($headers)
                            ->timeout(20)
                            ->post($url, [
                                'source_language' => $source,
                                'target_language' => $target,
                                'text' => $text,
                            ]);
                    }
                    return $requests;
                });

                foreach ($chunk as $i => $text) {
                    $response = $responses[(string) $i] ?? null;
                    $translated = $text;
                    if ($response && method_exists($response, 'successful') && $response->successful()) {
                        $body = $response->json() ?? [];
                        $candidate = $body['output']['translated_text']
                            ?? $body['translated_text']
                            ?? $body['text']
                            ?? null;
                        if (is_string($candidate) && $candidate !== '' && $candidate !== $text) {
                            $translated = $candidate;
                        }
                    }
                    $out[$text] = $translated;
                    if ($translated !== $text) {
                        Cache::forever($this->cacheKey($text, $target, $source), $translated);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Sunbird pool chunk failure', ['error' => $e->getMessage()]);
                foreach ($chunk as $text) {
                    if (!isset($out[$text])) $out[$text] = $text;
                }
            }
        }

        return $out;
    }

    protected function call(string $text, string $target, string $source): string
    {
        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->asJson()
                ->timeout(20)
                ->post("{$this->baseUrl}/tasks/translate", [
                    'source_language' => $source,
                    'target_language' => $target,
                    'text' => $text,
                ]);

            if (!$response->successful()) {
                Log::warning('Sunbird translate failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $text;
            }

            $body = $response->json() ?? [];
            $translated = $body['output']['translated_text']
                ?? $body['translated_text']
                ?? $body['text']
                ?? null;

            return is_string($translated) && $translated !== '' ? $translated : $text;
        } catch (\Throwable $e) {
            Log::warning('Sunbird translate exception', ['error' => $e->getMessage()]);
            return $text;
        }
    }

    protected function cacheKey(string $text, string $target, string $source): string
    {
        return 'sunbird:' . $source . ':' . $target . ':' . sha1($text);
    }
}
