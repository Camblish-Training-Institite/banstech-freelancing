<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subCategories = [
            ['name' => 'Graphic Design', 'parent_id' => 1],
            ['name' => 'Web Development', 'parent_id' => 1],
            ['name' => 'Plumbing', 'parent_id' => 2],
            ['name' => 'Electrical Work', 'parent_id' => 2],
            ['name' => 'Car Repair', 'parent_id' => 3],
            ['name' => 'Mechanical Services', 'parent_id' => 3],
            ['name' => 'Consulting', 'parent_id' => 4],
            ['name' => 'Accounting', 'parent_id' => 4],
            ['name' => 'Event Planning', 'parent_id' => 5],
            ['name' => 'Personal Training', 'parent_id' => 5],
        ];

        foreach ($subCategories as $subCategory) {
            \App\Models\SubCategory::factory()->create($subCategory);
        }

        
    }
}
