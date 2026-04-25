<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN DEFAULT
        User::create([
            'name' => 'Admin Keluhin',
            'email' => 'admin@keluhin.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // USER DUMMY
        User::factory(10)->create();
    }
}