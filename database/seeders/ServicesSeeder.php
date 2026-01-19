<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Diabetes Education',
                'description' => 'Personalized strategies for blood sugar management and living well with Type 1 or Type 2 diabetes.',
                'icon' => '💉',
                'icon_type' => 'emoji',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Nutrition Guidance',
                'description' => 'Evidence-based meal planning that honors your lifestyle and supports your health goals.',
                'icon' => '🌿',
                'icon_type' => 'emoji',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Pump Training',
                'description' => 'Expert instruction on insulin pumps and automated delivery systems including bionic pancreas.',
                'icon' => '⚙️',
                'icon_type' => 'emoji',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Speaking',
                'description' => 'Engaging presentations for healthcare groups, conferences, and community organizations.',
                'icon' => '🎤',
                'icon_type' => 'emoji',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title' => $service['title']],
                $service
            );
        }
    }
}
