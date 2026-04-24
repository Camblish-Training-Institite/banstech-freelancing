<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProposalAcceptanceMilestoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_open_the_accept_proposal_milestone_prompt(): void
    {
        $client = User::factory()->create();
        $freelancer = User::factory()->create();

        $job = Job::create([
            'user_id' => $client->id,
            'title' => 'Landing page build',
            'description' => 'Build a landing page',
            'budget' => 1200,
            'status' => 'open',
            'deadline' => now()->addWeeks(3),
            'job_funded' => false,
            'skills' => ['Laravel'],
        ]);

        $proposal = Proposal::create([
            'user_id' => $freelancer->id,
            'job_id' => $job->id,
            'bid_amount' => 1200,
            'cover_letter' => 'I can do this.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($client)->get(route('client.proposals.accept', $proposal));

        $response->assertOk();
        $response->assertSee('Number of milestones', false);
    }

    public function test_blank_milestone_count_creates_one_full_amount_milestone(): void
    {
        Notification::fake();

        $client = User::factory()->create();
        $freelancer = User::factory()->create();

        $job = Job::create([
            'user_id' => $client->id,
            'title' => 'Landing page build',
            'description' => 'Build a landing page',
            'budget' => 1200,
            'status' => 'open',
            'deadline' => now()->addWeeks(3),
            'job_funded' => false,
            'skills' => ['Laravel'],
        ]);

        $proposal = Proposal::create([
            'user_id' => $freelancer->id,
            'job_id' => $job->id,
            'bid_amount' => 1200,
            'cover_letter' => 'I can do this.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($client)->post(route('client.proposals.accept.store', $proposal), [
            'milestone_count' => '',
        ]);

        $response->assertRedirect(route('billing'));

        $contract = Contract::first();

        $this->assertNotNull($contract);
        $this->assertDatabaseHas('contracts', [
            'job_id' => $job->id,
            'user_id' => $freelancer->id,
            'agreed_amount' => '1200.00',
        ]);
        $this->assertDatabaseHas('milestones', [
            'project_id' => $contract->id,
            'title' => 'Full project delivery',
            'amount' => '1200.00',
            'status' => 'approved',
        ]);
    }

    public function test_selected_milestone_count_splits_budget_equally(): void
    {
        Notification::fake();

        $client = User::factory()->create();
        $freelancer = User::factory()->create();

        $job = Job::create([
            'user_id' => $client->id,
            'title' => 'Dashboard build',
            'description' => 'Build a dashboard',
            'budget' => 1000,
            'status' => 'open',
            'deadline' => now()->addWeeks(4),
            'job_funded' => false,
            'skills' => ['PHP'],
        ]);

        $proposal = Proposal::create([
            'user_id' => $freelancer->id,
            'job_id' => $job->id,
            'bid_amount' => 1000,
            'cover_letter' => 'I can do this.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($client)->post(route('client.proposals.accept.store', $proposal), [
            'milestone_count' => 3,
        ]);

        $response->assertRedirect(route('billing'));

        $contract = Contract::first();
        $milestones = $contract->milestones()->orderBy('id')->get();

        $this->assertCount(3, $milestones);
        $this->assertEquals(333.33, (float) $milestones[0]->amount);
        $this->assertEquals(333.33, (float) $milestones[1]->amount);
        $this->assertEquals(333.34, (float) $milestones[2]->amount);
        $this->assertEquals(1000.00, (float) $milestones->sum('amount'));
    }
}
