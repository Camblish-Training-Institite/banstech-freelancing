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
        //create all roles and permissions that will be used by the application
        $this->call([
            RolesAndPermissionsSeeder::class,
            // Add any other seeders here, e.g., UserSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Reed Richards',
            'email' => 'reed@fant4stic.com',
            'password' => bcrypt('mrfantastic'),
            'user_type' => 'admin'
        ])->assignRole('admin');
    }
}
