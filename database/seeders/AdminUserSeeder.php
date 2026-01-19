<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'mchamberlain8@gmail.com'],
            [
                'name' => 'Maggie Chamberlain',
                'email' => 'mchamberlain8@gmail.com',
                'password' => Hash::make('change-me'),
                'email_verified_at' => now(),
            ]
        );
    }
}