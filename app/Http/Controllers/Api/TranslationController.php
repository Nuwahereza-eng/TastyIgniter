<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SunbirdTranslator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(protected SunbirdTranslator $translator)
    {
    }

    /**
     * GET /api/translate/languages
     * Returns the list of languages supported by the switcher.
     */
    public function languages(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'configured' => $this->translator->isConfigured(),
            'languages' => SunbirdTranslator::LANGUAGES,
        ]);
    }

    /**
     * GET /ajax/translate/dictionary/{lang}
     * Returns the built-in static dictionary of common UI strings for a
     * given language. Loaded once by the switcher on first activation so
     * the bulk of the UI translates instantly without any API call.
     */
    public function dictionary(string $lang): JsonResponse
    {
        $lang = strtolower($lang);
        if (!SunbirdTranslator::isSupported($lang)) {
            return response()->json(['success' => false, 'message' => 'Unsupported language'], 422);
        }

        $path = base_path('resources/lang/ui-dictionary.php');
        $dict = is_file($path) ? (require $path) : [];

        return response()
            ->json([
                'success' => true,
                'target' => $lang,
                'translations' => $dict[$lang] ?? new \stdClass(),
            ])
            ->header('Cache-Control', 'public, max-age=86400'); // 24h browser cache
    }

    /**
     * POST /api/translate
     * Body: { "target": "lug", "texts": ["Hello", "Welcome"] }
     * Returns: { "success": true, "target": "lug", "translations": { "Hello": "...", "Welcome": "..." } }
     */
    public function translate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'target' => 'required|string|size:3',
            'source' => 'nullable|string|size:3',
            'texts' => 'required|array|min:1|max:50',
            'texts.*' => 'string|max:2000',
        ]);

        $target = strtolower($data['target']);
        $source = strtolower($data['source'] ?? 'eng');

        if (!SunbirdTranslator::isSupported($target)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported target language.',
            ], 422);
        }

        $translations = $this->translator->translateMany($data['texts'], $target, $source);

        return response()->json([
            'success' => true,
            'target' => $target,
            'translations' => $translations,
        ]);
    }
}
