<?php

namespace Database\Seeders;

use App\Models\User;
use App\MOdels\Job;
use App\Models\Proposal;
use App\Models\Contract;
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
        // $this->call([
        //     RolesAndPermissionsSeeder::class,
        //     // Add any other seeders here, e.g., UserSeeder::class,
        // ]);

        //     $this->call(CategorySeeder::class);

        //     $this->call(SubCategorySeeder::class);

        // User::factory()->create([
        //     'name' => 'Reed Richards',
        //     'email' => 'reed@fant4stic.com',
        //     'password' => bcrypt('mrfantastic'),
        //     'user_type' => 'admin'
        // ])->assignRole('admin');

        // User::factory()->create([
        //     'name' => 'John Doe',
        //     'email' => 'john@mail.com',
        //     'password' => bcrypt('12345678'),
        //     'user_type' => 'freelancer-client'
        // ])->assignRole('freelancer-client');

        // User::factory()->create([
        //     'name' => 'Coolio Jones',
        //     'email' => 'coolio@mail.com',
        //     'password' => bcrypt('12345678'),
        //     'user_type' => 'freelancer-client'
        // ])->assignRole('freelancer-client');

        // User::factory()->create([
        //     'name' => 'Ameila Earhart',
        //     'email' => 'ameila@mail.com',
        //     'password' => bcrypt('12345678'),
        //     'user_type' => 'freelancer-client'
        // ])->assignRole('freelancer-client');

        // Job::factory(6)->create();

        Proposal::factory(50)->create();

        Contract::factory(20)->create();    
    }
}
