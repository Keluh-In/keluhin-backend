<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@keluhin.com'],
            [
                'name' => 'Admin Keluhin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@keluhin.com'],
            [
                'name' => 'Super Admin Keluhin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
            ]
        );

        if (User::where('role', 'user')->doesntExist()) {
            User::factory(10)->create();
        }
    }
}
