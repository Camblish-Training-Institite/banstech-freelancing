<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'completed', 'closed']),
            'budget' => $this->faker->numberBetween(100, 10000),
            'deadline' => $this->faker->dateTimeBetween('now', '+1 year'),
            'job_funded' => $this->faker->boolean(), 
            'skills' => $this->faker->randomElements(['PHP', 'JavaScript', 'Laravel', 'Vue.js', 'React', 'CSS', 'HTML'], $this->faker->numberBetween(1, 5)),
        ];
    }
}
