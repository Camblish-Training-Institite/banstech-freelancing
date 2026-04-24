<?php

use App\Models\Payout;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WithdrawalStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('lets a freelancer request a paypal withdrawal from available earnings', function () {
    Notification::fake();

    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
    config()->set('services.paypal.currency', 'USD');

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v1/payments/payouts' => Http::response([
            'batch_header' => [
                'payout_batch_id' => 'WD-BATCH-100',
                'batch_status' => 'PENDING',
            ],
            'items' => [[
                'sender_item_id' => 'withdrawal-1',
            ]],
        ], 201),
    ]);

    $freelancer = User::factory()->create([
        'email' => 'freelancer@example.com',
    ]);

    Payout::create([
        'freelancer_id' => $freelancer->id,
        'amount' => 250,
        'method' => 'paypal',
        'status' => 'processed',
        'requested_at' => now(),
        'processed_at' => now(),
    ]);

    $response = $this->actingAs($freelancer)->post(route('freelancer.withdrawals.store'), [
        'amount' => 100,
        'method' => 'paypal',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    $request = WithdrawalRequest::first();

    expect($request)->not->toBeNull();
    expect($request->amount)->toBe('100.00');
    expect($request->method)->toBe('paypal');
    expect($request->status)->toBe('confirmed');
    expect(data_get($request->destination_details, 'email'))->toBe('freelancer@example.com');
    expect($request->paypal_batch_id)->toBe('WD-BATCH-100');
    expect($request->paypal_recipient_email)->toBe('freelancer@example.com');
    expect($request->confirmed_at)->not->toBeNull();

    Notification::assertSentTo($freelancer, WithdrawalStatusUpdated::class);
});

it('prevents a freelancer from withdrawing more than the available balance', function () {
    $freelancer = User::factory()->create();

    Payout::create([
        'freelancer_id' => $freelancer->id,
        'amount' => 120,
        'method' => 'paypal',
        'status' => 'processed',
        'requested_at' => now(),
        'processed_at' => now(),
    ]);

    WithdrawalRequest::create([
        'user_id' => $freelancer->id,
        'amount' => 50,
        'method' => 'paypal',
        'status' => 'pending',
        'destination_details' => ['email' => $freelancer->email],
        'requested_at' => now(),
    ]);

    $response = $this->actingAs($freelancer)->from(route('freelancer.earnings'))->post(route('freelancer.withdrawals.store'), [
        'amount' => 100,
        'method' => 'paypal',
    ]);

    $response->assertRedirect(route('freelancer.earnings'));
    $response->assertSessionHasErrors('amount');

    expect(WithdrawalRequest::count())->toBe(1);
});

it('requires saved bank details before allowing a bank withdrawal request', function () {
    $freelancer = User::factory()->create();

    Payout::create([
        'freelancer_id' => $freelancer->id,
        'amount' => 120,
        'method' => 'paypal',
        'status' => 'processed',
        'requested_at' => now(),
        'processed_at' => now(),
    ]);

    $response = $this->actingAs($freelancer)->from(route('freelancer.earnings'))->post(route('freelancer.withdrawals.store'), [
        'amount' => 50,
        'method' => 'bank',
    ]);

    $response->assertRedirect(route('freelancer.earnings'));
    $response->assertSessionHasErrors('method');

    expect(WithdrawalRequest::count())->toBe(0);
});

it('syncs pending paypal milestone payouts into available withdrawals on the earnings page', function () {
    config()->set('services.paypal.client_id', 'test-client');
    config()->set('services.paypal.client_secret', 'test-secret');
    config()->set('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'test-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v1/payments/payouts/BATCH-1200*' => Http::response([
            'batch_header' => [
                'payout_batch_id' => 'BATCH-1200',
                'batch_status' => 'SUCCESS',
            ],
            'items' => [[
                'payout_item_id' => 'ITEM-1200',
                'transaction_status' => 'UNCLAIMED',
                'payout_item' => [
                    'receiver' => 'dietrich.jessie@example.com',
                ],
            ]],
        ], 200),
    ]);

    $freelancer = User::factory()->create([
        'email' => 'dietrich.jessie@example.com',
        'name' => 'Dr. Joannie Collins',
    ]);

    Payout::create([
        'freelancer_id' => $freelancer->id,
        'amount' => 1200,
        'method' => 'paypal',
        'status' => 'pending',
        'requested_at' => now(),
        'paypal_batch_id' => 'BATCH-1200',
        'paypal_recipient_email' => 'dietrich.jessie@example.com',
        'gateway_response' => [
            'batch_header' => [
                'batch_status' => 'PENDING',
            ],
        ],
    ]);

    $response = $this->actingAs($freelancer)->get(route('freelancer.earnings'));

    $response->assertOk();
    $response->assertSee('R1,200.00');

    $payout = Payout::first();

    expect($payout->fresh()->status)->toBe('processed');
    expect($payout->fresh()->processed_at)->not->toBeNull();
    expect($payout->fresh()->paypal_payout_item_id)->toBe('ITEM-1200');
});

it('allows an admin to confirm and process a bank withdrawal request', function () {
    Notification::fake();

    $admin = User::factory()->create();
    $freelancer = User::factory()->create();

    $freelancer->bankDetail()->create([
        'account_holder_name' => 'Freelancer Example',
        'bank_name' => 'FNB',
        'account_number' => '12345678901',
        'account_type' => 'checking',
        'branch_code' => '250655',
        'swift_code' => 'FIRNZAJJ',
    ]);

    $withdrawalRequest = WithdrawalRequest::create([
        'user_id' => $freelancer->id,
        'amount' => 300,
        'method' => 'bank',
        'status' => 'pending',
        'destination_details' => [
            'account_holder_name' => 'Freelancer Example',
            'bank_name' => 'FNB',
            'account_number' => '12345678901',
        ],
        'requested_at' => now(),
    ]);

    $confirmResponse = $this->actingAs($admin, 'admin')
        ->patch(route('admin.withdrawals.confirm', $withdrawalRequest));

    $confirmResponse->assertRedirect();

    expect($withdrawalRequest->fresh()->status)->toBe('confirmed');
    expect($withdrawalRequest->fresh()->reviewed_by_admin_id)->toBe($admin->id);
    expect($withdrawalRequest->fresh()->confirmed_at)->not->toBeNull();

    $processResponse = $this->actingAs($admin, 'admin')
        ->patch(route('admin.withdrawals.process', $withdrawalRequest));

    $processResponse->assertRedirect();

    expect($withdrawalRequest->fresh()->status)->toBe('processed');
    expect($withdrawalRequest->fresh()->processed_at)->not->toBeNull();

    Notification::assertSentToTimes($freelancer, WithdrawalStatusUpdated::class, 2);
});

it('blocks admins from marking paypal withdrawals as processed without a paypal batch id', function () {
    $admin = User::factory()->create();
    $freelancer = User::factory()->create([
        'email' => 'freelancer@example.com',
    ]);

    $withdrawalRequest = WithdrawalRequest::create([
        'user_id' => $freelancer->id,
        'amount' => 150,
        'method' => 'paypal',
        'status' => 'confirmed',
        'destination_details' => ['email' => 'freelancer@example.com'],
        'requested_at' => now(),
        'confirmed_at' => now(),
    ]);

    $response = $this->actingAs($admin, 'admin')
        ->from(route('admin.withdrawals.show', $withdrawalRequest))
        ->patch(route('admin.withdrawals.process', $withdrawalRequest));

    $response->assertRedirect(route('admin.withdrawals.show', $withdrawalRequest));
    $response->assertSessionHasErrors('withdrawal');

    expect($withdrawalRequest->fresh()->status)->toBe('confirmed');
});

it('notifies the freelancer when a withdrawal is marked as failed', function () {
    Notification::fake();

    $admin = User::factory()->create();
    $freelancer = User::factory()->create();

    $withdrawalRequest = WithdrawalRequest::create([
        'user_id' => $freelancer->id,
        'amount' => 150,
        'method' => 'bank',
        'status' => 'confirmed',
        'destination_details' => ['bank_name' => 'FNB'],
        'requested_at' => now(),
        'confirmed_at' => now(),
    ]);

    $response = $this->actingAs($admin, 'admin')
        ->patch(route('admin.withdrawals.fail', $withdrawalRequest), [
            'failure_reason' => 'Transfer failed at the bank.',
        ]);

    $response->assertRedirect();

    expect($withdrawalRequest->fresh()->status)->toBe('failed');
    expect($withdrawalRequest->fresh()->failure_reason)->toBe('Transfer failed at the bank.');

    Notification::assertSentTo($freelancer, WithdrawalStatusUpdated::class);
});
