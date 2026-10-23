<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The app is TastyIgniter; "UgaEats" is only the name of one branch.
 * Rename the app name in site settings and the static pages. Locations
 * (branches) are deliberately not touched.
 */
return new class extends Migration {
    public function up(): void
    {
        foreach (['site_name', 'sender_name'] as $item) {
            $value = DB::table('settings')->where('sort', 'config')->where('item', $item)->value('value');
            if (is_string($value) && stripos($value, 'ugaeats') !== false) {
                DB::table('settings')->where('sort', 'config')->where('item', $item)
                    ->update(['value' => $this->rename($value)]);
            }
        }

        $siteEmail = DB::table('settings')->where('sort', 'config')->where('item', 'site_email')->value('value')
            ?: env('MAIL_FROM_ADDRESS', '');

        DB::table('pages')->orderBy('page_id')->get()->each(function ($page) use ($siteEmail) {
            $updates = [];
            foreach (['title', 'content', 'meta_description', 'meta_keywords'] as $column) {
                $value = $page->$column;
                if (!is_string($value) || stripos($value, 'ugaeats') === false) {
                    continue;
                }
                // Made-up addresses such as hello@ugaeats.ug -> the real site email.
                if ($siteEmail) {
                    $value = preg_replace('/[a-z0-9._%+-]+@ugaeats\.[a-z.]+/i', $siteEmail, $value);
                }
                $updates[$column] = $this->rename($value);
            }

            if ($updates) {
                DB::table('pages')->where('page_id', $page->page_id)->update($updates);
            }
        });
    }

    public function down(): void
    {
        // Not reversible.
    }

    protected function rename(string $value): string
    {
        return str_ireplace(['UgaEatsUG', 'UgaEats Uganda', 'UgaEats'], ['TastyIgniter', 'TastyIgniter', 'TastyIgniter'], $value);
    }
};
