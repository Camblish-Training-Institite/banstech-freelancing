<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $job = Job::where('status', 'in_progress')->inRandomOrder()->first();

        // If no in_progress job exists, create one
        if (!$job) {
            $job = Job::factory()->inProgress()->create();
        }

        return [
            'job_id' => $job->id,
            'user_id' => \App\Models\User::factory(), // assuming user factory exists
            'agreed_amount' => $job->budget, // or generate separately
            'start_date' => now()->subDays(rand(1, 30)),
            'end_date' => now()->addDays(rand(30, 90)),
            'status' => $this->faker->randomElement(['active', 'completed', 'cancelled']),
            'project_manager_id' => \App\Models\User::factory(), // optional: assign PM
        ];
    }
}
