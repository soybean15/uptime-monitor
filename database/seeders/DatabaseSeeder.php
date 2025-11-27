<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@stupendio.it',
        ]);

        // Create marlon user
        User::factory()->create([
            'name' => 'Marlon',
            'email' => 'marlonpadilla1593@gmail.com',
        ]);

        // Create Paolo user
        User::factory()->create([
            'name' => 'Paolo',
            'email' => 'paolo@stupendio.it',
        ]);
    }
}
