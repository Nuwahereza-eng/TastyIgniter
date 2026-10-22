<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Repair addresses saved with a blank "Address 1" (a single space) by the
 * checkout location pre-fill. They made the admin customer form fail with
 * "The Address 1 field is required." New addresses are handled by the
 * Address::saving hook in AppServiceProvider.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::table('addresses')
            ->whereRaw('CHAR_LENGTH(TRIM(COALESCE(address_1, ""))) < 3')
            ->orderBy('address_id')
            ->get(['address_id', 'address_2', 'city', 'state'])
            ->each(function ($address) {
                $fill = collect([$address->address_2, $address->city, $address->state])
                    ->map(fn ($part) => trim((string)$part))
                    ->first(fn ($part) => strlen($part) >= 3) ?? 'Pinned location';

                DB::table('addresses')->where('address_id', $address->address_id)->update(['address_1' => $fill]);
            });
    }

    public function down(): void
    {
        // Not reversible: the original value was a blank string.
    }
};
