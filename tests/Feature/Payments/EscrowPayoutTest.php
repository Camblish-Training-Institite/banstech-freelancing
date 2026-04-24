<?php

use App\Models\Contract;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Payout;
use App\Models\ProjectFunding;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('redirects the client to paypal when starting a wallet top-up', function () {
    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
    config()->set('services.paypal.currency', 'USD');

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v2/checkout/orders' => Http::response([
            'id' => 'ORDER-123',
            'status' => 'CREATED',
            'links' => [[
                'rel' => 'approve',
                'href' => 'https://www.sandbox.paypal.com/checkoutnow?token=ORDER-123',
            ]],
        ], 201),
    ]);

    $client = User::factory()->create();

    $response = $this->actingAs($client)->post(route('billing.deposits.store'), [
        'amount' => 150,
    ]);

    $response->assertRedirect('https://www.sandbox.paypal.com/checkoutnow?token=ORDER-123');

    $deposit = ProjectFunding::latest()->first();

    expect($deposit->status)->toBe('pending');
    expect($deposit->paypal_order_id)->toBe('ORDER-123');
    expect($deposit->payment_gateway)->toBe('paypal');
});

it('converts unsupported regional currency to a paypal supported currency for checkout', function () {
    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
    config()->set('services.paypal.fallback_currency', 'USD');
    config()->set('services.paypal.default_local_currency', 'ZAR');
    config()->set('services.fx.base_url', 'https://api.frankfurter.dev');

    Http::fake([
        'https://api.frankfurter.dev/v2/rate/ZAR/USD' => Http::response([
            'amount' => 1,
            'base' => 'ZAR',
            'quote' => 'USD',
            'rate' => 0.054,
        ], 200),
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v2/checkout/orders' => Http::response([
            'id' => 'ORDER-ZA-123',
            'status' => 'CREATED',
            'links' => [[
                'rel' => 'approve',
                'href' => 'https://www.sandbox.paypal.com/checkoutnow?token=ORDER-ZA-123',
            ]],
        ], 201),
    ]);

    $client = User::factory()->create();
    Profile::create([
        'user_id' => $client->id,
        'country' => 'South Africa',
    ]);

    $response = $this->actingAs($client)->post(route('billing.deposits.store'), [
        'amount' => 100,
    ]);

    $response->assertRedirect('https://www.sandbox.paypal.com/checkoutnow?token=ORDER-ZA-123');

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->url() === 'https://api-m.sandbox.paypal.com/v2/checkout/orders'
            && data_get($data, 'purchase_units.0.amount.currency_code') === 'USD'
            && data_get($data, 'purchase_units.0.amount.value') === '5.40';
    });

    $deposit = ProjectFunding::latest()->first();

    expect($deposit->source_currency)->toBe('ZAR');
    expect($deposit->paypal_currency)->toBe('USD');
    expect($deposit->paypal_amount)->toBe('5.40');
});

it('credits the wallet only after paypal capture succeeds', function () {
    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
    config()->set('services.paypal.currency', 'USD');

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v2/checkout/orders/ORDER-123/capture' => Http::response([
            'status' => 'COMPLETED',
            'purchase_units' => [[
                'payments' => [
                    'captures' => [[
                        'id' => 'CAPTURE-123',
                        'status' => 'COMPLETED',
                    ]],
                ],
            ]],
        ], 201),
    ]);

    $client = User::factory()->create();

    $deposit = ProjectFunding::create([
        'client_id' => $client->id,
        'amount' => 200,
        'payment_gateway' => 'paypal',
        'paypal_order_id' => 'ORDER-123',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($client)->get(route('billing.deposits.approve', $deposit) . '?token=ORDER-123');

    $response->assertRedirect(route('billing'));

    $deposit->refresh();

    expect($deposit->status)->toBe('deposited');
    expect($deposit->paypal_capture_id)->toBe('CAPTURE-123');
    expect($deposit->processed_at)->not->toBeNull();
});

it('moves wallet funds into escrow for a funded job', function () {
    $client = User::factory()->create();
    $freelancer = User::factory()->create();

    $job = Job::create([
        'user_id' => $client->id,
        'title' => 'Landing page build',
        'description' => 'Build a landing page',
        'budget' => 500,
        'status' => 'assigned',
        'deadline' => now()->addWeek(),
        'job_funded' => false,
        'skills' => ['Laravel'],
    ]);

    Contract::create([
        'job_id' => $job->id,
        'user_id' => $freelancer->id,
        'agreed_amount' => 500,
        'start_date' => now(),
        'end_date' => now()->addWeek(),
        'status' => 'active',
    ]);

    ProjectFunding::create([
        'client_id' => $client->id,
        'amount' => 800,
        'status' => 'deposited',
    ]);

    $response = $this->actingAs($client)->post(route('billing.jobs.fund', $job), [
        'amount' => 500,
    ]);

    $response->assertRedirect();

    expect(ProjectFunding::where('job_id', $job->id)->where('status', 'pending')->value('amount'))
        ->toBe('500.00');

    expect((bool) $job->fresh()->job_funded)->toBeTrue();
});

it('releases a milestone payout through paypal and reduces escrow', function () {
    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
    config()->set('services.paypal.currency', 'USD');
    config()->set('services.paypal.default_local_currency', 'USD');

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v1/payments/payouts' => Http::response([
            'batch_header' => [
                'payout_batch_id' => 'batch-123',
                'batch_status' => 'SUCCESS',
            ],
            'items' => [[
                'payout_item_id' => 'item-123',
            ]],
        ], 201),
    ]);

    $client = User::factory()->create();
    $freelancer = User::factory()->create([
        'email' => 'freelancer@example.com',
    ]);

    $job = Job::create([
        'user_id' => $client->id,
        'title' => 'API integration',
        'description' => 'Build payment release',
        'budget' => 600,
        'status' => 'assigned',
        'deadline' => now()->addWeek(),
        'job_funded' => true,
        'skills' => ['PHP'],
    ]);

    $contract = Contract::create([
        'job_id' => $job->id,
        'user_id' => $freelancer->id,
        'agreed_amount' => 600,
        'start_date' => now(),
        'end_date' => now()->addWeek(),
        'status' => 'active',
    ]);

    $milestone = Milestone::create([
        'project_id' => $contract->id,
        'title' => 'Phase 1',
        'description' => 'Initial milestone',
        'amount' => 200,
        'due_date' => now()->addDays(3),
        'status' => 'approved',
    ]);

    ProjectFunding::create([
        'client_id' => $client->id,
        'job_id' => $job->id,
        'amount' => 500,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($client)->get(route('client.project.milestone.release', [$contract, $milestone]));

    $response->assertRedirect();

    expect($milestone->fresh()->status)->toBe('released');
    expect(ProjectFunding::where('job_id', $job->id)->where('status', 'pending')->value('amount'))
        ->toBe('300.00');

    $payout = Payout::where('contract_id', $contract->id)->first();

    expect($payout)->not->toBeNull();
    expect($payout->status)->toBe('processed');
    expect($payout->method)->toBe('paypal');
    expect($payout->milestone_id)->toBe($milestone->id);
    expect($payout->paypal_recipient_email)->toBe('freelancer@example.com');
});
