<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin 👑
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Representante 🧑‍💼
        User::updateOrCreate(
            ['email' => 'rep@example.com'],
            [
                'name' => 'Representante',
                'password' => Hash::make('password'),
                'role' => 'representante',
                'email_verified_at' => now(),
            ]
        );
    }
}
