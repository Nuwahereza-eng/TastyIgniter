<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LusaniyaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates the Lusaniya (Group Platters) category for the menu.
     */
    public function run(): void
    {
        // Check if Lusaniya category already exists
        $exists = DB::table('categories')->where('permalink_slug', 'lusaniya')->exists();
        
        if (!$exists) {
            // Get the highest priority to add this at the end
            $maxPriority = DB::table('categories')->max('priority') ?? 0;
            
            // Insert the Lusaniya category
            DB::table('categories')->insert([
                'name' => 'Lusaniya',
                'description' => 'Traditional Ugandan group platters - perfect for sharing with family and friends. Large portions meant to feed 4-8 people.',
                'permalink_slug' => 'lusaniya',
                'priority' => $maxPriority + 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Lusaniya category created successfully!');
        } else {
            $this->command->info('Lusaniya category already exists.');
        }
    }
}
