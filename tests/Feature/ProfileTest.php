<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'bio' => 'Full-stack developer',
            'city' => 'Austin',
            'country' => 'USA',
            'timezone' => 'America/Chicago',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
        ]);
    }

    public function test_profile_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
    }
}
