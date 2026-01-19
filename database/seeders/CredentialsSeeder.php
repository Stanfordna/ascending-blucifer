<?php

namespace Database\Seeders;

use App\Models\Credential;
use Illuminate\Database\Seeder;

class CredentialsSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = [
            [
                'name' => 'Master of Science',
                'abbreviation' => 'MS',
                'institution' => 'Louisiana Tech University',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Registered Dietitian',
                'abbreviation' => 'RD',
                'institution' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Certified Diabetes Care and Education Specialist',
                'abbreviation' => 'CDCES',
                'institution' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'MSU Denver',
                'abbreviation' => null,
                'institution' => 'MSU Denver',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($credentials as $credential) {
            Credential::updateOrCreate(
                ['name' => $credential['name']],
                $credential
            );
        }
    }
}
