<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update section names: hero -> welcome, cta -> contact
        DB::table('content_blocks')
            ->where('section', 'hero')
            ->update(['section' => 'welcome']);

        DB::table('content_blocks')
            ->where('section', 'cta')
            ->update(['section' => 'contact']);

        // Update key prefixes: hero_ -> welcome_, cta_ -> contact_
        DB::table('content_blocks')
            ->where('key', 'like', 'hero_%')
            ->get()
            ->each(function ($block) {
                DB::table('content_blocks')
                    ->where('id', $block->id)
                    ->update(['key' => str_replace('hero_', 'welcome_', $block->key)]);
            });

        DB::table('content_blocks')
            ->where('key', 'like', 'cta_%')
            ->get()
            ->each(function ($block) {
                DB::table('content_blocks')
                    ->where('id', $block->id)
                    ->update(['key' => str_replace('cta_', 'contact_', $block->key)]);
            });
    }

    public function down(): void
    {
        // Revert section names: welcome -> hero, contact -> cta
        DB::table('content_blocks')
            ->where('section', 'welcome')
            ->update(['section' => 'hero']);

        DB::table('content_blocks')
            ->where('section', 'contact')
            ->update(['section' => 'cta']);

        // Revert key prefixes: welcome_ -> hero_, contact_ -> cta_
        DB::table('content_blocks')
            ->where('key', 'like', 'welcome_%')
            ->get()
            ->each(function ($block) {
                DB::table('content_blocks')
                    ->where('id', $block->id)
                    ->update(['key' => str_replace('welcome_', 'hero_', $block->key)]);
            });

        DB::table('content_blocks')
            ->where('key', 'like', 'contact_%')
            ->get()
            ->each(function ($block) {
                DB::table('content_blocks')
                    ->where('id', $block->id)
                    ->update(['key' => str_replace('contact_', 'cta_', $block->key)]);
            });
    }
};
