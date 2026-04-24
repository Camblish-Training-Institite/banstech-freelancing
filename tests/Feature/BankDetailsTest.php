<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_freelancer_can_store_bank_details_from_profile_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('freelancer.profile.updateBankDetails'), [
            'account_holder_name' => 'John Doe',
            'bank_name' => 'FNB',
            'account_number' => '12345678901',
            'account_type' => 'checking',
            'branch_code' => '250655',
            'swift_code' => 'FIRNZAJJ',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bank_details', [
            'user_id' => $user->id,
            'account_holder_name' => 'John Doe',
            'bank_name' => 'FNB',
            'account_number' => '12345678901',
            'account_type' => 'checking',
            'branch_code' => '250655',
            'swift_code' => 'FIRNZAJJ',
        ]);
    }

    public function test_bank_details_can_be_cleared_when_all_fields_are_empty(): void
    {
        $user = User::factory()->create();
        $user->bankDetail()->create([
            'account_holder_name' => 'John Doe',
            'bank_name' => 'FNB',
            'account_number' => '12345678901',
            'account_type' => 'checking',
            'branch_code' => '250655',
            'swift_code' => 'FIRNZAJJ',
        ]);

        $response = $this->actingAs($user)->patch(route('freelancer.profile.updateBankDetails'), [
            'account_holder_name' => '',
            'bank_name' => '',
            'account_number' => '',
            'account_type' => '',
            'branch_code' => '',
            'swift_code' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('bank_details', [
            'user_id' => $user->id,
        ]);
    }
}
