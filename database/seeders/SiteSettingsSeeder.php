<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'group' => 'general', 'value' => 'Maggie Chamberlain, MS, RD, CDCES', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'group' => 'general', 'value' => 'Diabetes Care & Nutrition', 'type' => 'text', 'label' => 'Tagline'],

            // Colors
            ['key' => 'color_primary', 'group' => 'colors', 'value' => '#4A7C89', 'type' => 'color', 'label' => 'Primary Color (Mountain Blue)'],
            ['key' => 'color_secondary', 'group' => 'colors', 'value' => '#C4785A', 'type' => 'color', 'label' => 'Secondary Color (Terracotta)'],
            ['key' => 'color_accent', 'group' => 'colors', 'value' => '#D4A84B', 'type' => 'color', 'label' => 'Accent Color (Gold)'],

            // Contact
            ['key' => 'contact_email', 'group' => 'contact', 'value' => 'maggie@example.com', 'type' => 'email', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'group' => 'contact', 'value' => '', 'type' => 'text', 'label' => 'Contact Phone'],
            ['key' => 'contact_location', 'group' => 'contact', 'value' => 'Denver, Colorado', 'type' => 'text', 'label' => 'Location'],

            // Social
            ['key' => 'social_linkedin', 'group' => 'social', 'value' => '', 'type' => 'url', 'label' => 'LinkedIn URL'],
            ['key' => 'social_instagram', 'group' => 'social', 'value' => '', 'type' => 'url', 'label' => 'Instagram URL'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
