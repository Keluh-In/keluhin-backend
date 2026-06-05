<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // USER + ADMIN
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);

        // COMPLAINT DUMMY (biar dashboard langsung jalan)
        Complaint::factory(30)->create();
    }
}