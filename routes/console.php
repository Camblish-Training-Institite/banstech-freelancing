<?php

use App\Models\WithdrawalRequest;
use App\Services\Payments\PayPalPayoutSyncService;
use App\Services\Payments\WithdrawalProcessingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('paypal:sync-statuses', function () {
    app(PayPalPayoutSyncService::class)->syncPendingPayouts();

    app(WithdrawalProcessingService::class)->syncPayPalRequests(
        WithdrawalRequest::where('method', 'paypal')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNotNull('paypal_batch_id')
            ->get()
    );

    $this->info('PayPal payout and withdrawal statuses synced successfully.');
})->purpose('Sync PayPal payout and withdrawal statuses');

Schedule::command('paypal:sync-statuses')->everyFiveMinutes();
