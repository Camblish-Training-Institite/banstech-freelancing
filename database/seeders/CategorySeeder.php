<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is a list of all parent categories and their respective subcategories.
        // $categories = [
        //     'Digital & Creative' => [
        //         'Web, Mobile & Software Development',
        //         'Graphic & Brand Design',
        //         'Writing & Translation',
        //         'Digital Marketing & SEO',
        //         'Video & Animation',
        //         'Music & Audio Production'
        //     ],
        //     'Home & Trade Services' => [
        //         'Plumbing & Gas Fitting',
        //         'Electrical Work',
        //         'Carpentry & Construction',
        //         'Painting & Decorating',
        //         'Landscaping & Gardening',
        //         'Appliance Repair',
        //         'General Handyman Services'
        //     ],
        //     'Automotive & Mechanical' => [
        //         'Auto Mechanic Services',
        //         'Tire & Wheel Services',
        //         'Towing & Roadside Assistance',
        //         'Car Wash & Detailing'
        //     ],
        //     'Business & Professional Services' => [
        //         'Accounting & Financial Consulting',
        //         'Legal Services & Advice',
        //         'Admin & Virtual Assistant Support'
        //     ],
        //     'Events & Personal Services' => [
        //         'Event Planning & Management',
        //         'Photography & Videography',
        //         'Catering & Personal Chef',
        //         'Beauty & Wellness',
        //         'Personal Training & Fitness'
        //     ]
        // ];

        // // Loop through each parent category in the array.
        // foreach ($categories as $parentName => $subcategories) {
        //     // Create the parent category using the factory.
        //     $parent = Category::factory()->create([
        //         'name' => $parentName,
        //         'parent_id' => null
        //     ]);

        //     // Now, loop through the subcategories for the current parent.
        //     foreach ($subcategories as $subcategoryName) {
        //         // Create each subcategory, linking it to the parent we just created.
        //         Category::factory()->create([
        //             'name' => $subcategoryName,
        //             'parent_id' => $parent->id
        //         ]);
        //     }
        // }

        $categories = [
            'Digital & Creative',
            'Home & Trade Services',
            'Automotive & Mechanical',
            'Business & Professional Services',
            'Events & Personal Services'
        ];

        foreach ($categories as $categoryName) {
            Category::factory()->create([
                'name' => $categoryName,
                // 'parent_id' => null
            ]);
        }
    }
}
