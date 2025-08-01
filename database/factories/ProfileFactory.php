<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'bio' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'company' => fake()->company(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'timezone' => 'America/New_York',
            'avatar' => 'avatars/avatar.jpg',
        ];
    }
}
