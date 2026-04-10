<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $job = Job::inRandomOrder()->first();
        if (!$job) {
            $job = Job::factory()->create();
        }

        // Get a random user (freelancer applying for the job)
        $user = User::inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }

        return [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'bid_amount' => $this->faker->randomFloat(2, 50, 5000), // e.g., 1299.99
            'cover_letter' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
