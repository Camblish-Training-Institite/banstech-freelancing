<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class PayPalPayoutService
{
    public function isConfigured(): bool
    {
        return filled(config('services.paypal.client_id'))
            && filled(config('services.paypal.client_secret'));
    }

    public function sendPayout(string $recipientEmail, float $amount, string $currency, string $subject, string $note, ?string $senderItemId = null): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('PayPal payouts are not configured.');
        }

        if ($amount <= 0) {
            throw new RuntimeException('Payout amount must be greater than zero.');
        }

        $senderItemId = $senderItemId ?: (string) Str::uuid();
        $accessToken = $this->createAccessToken();
        $baseUrl = rtrim(config('services.paypal.base_url', 'https://api-m.sandbox.paypal.com'), '/');

        $response = Http::asJson()
            ->acceptJson()
            ->withToken($accessToken)
            ->withHeaders([
                'PayPal-Request-Id' => $senderItemId,
            ])
            ->timeout(30)
            ->post($baseUrl . '/v1/payments/payouts', [
                'sender_batch_header' => [
                    'sender_batch_id' => $senderItemId,
                    'email_subject' => $subject,
                    'email_message' => $note,
                ],
                'items' => [[
                    'recipient_type' => 'EMAIL',
                    'receiver' => $recipientEmail,
                    'note' => $note,
                    'sender_item_id' => $senderItemId,
                    'amount' => [
                        'value' => number_format($amount, 2, '.', ''),
                        'currency' => strtoupper($currency),
                    ],
                ]],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('PayPal payout request failed: ' . $response->body());
        }

        $payload = $response->json();

        return [
            'batch_id' => data_get($payload, 'batch_header.payout_batch_id'),
            'batch_status' => data_get($payload, 'batch_header.batch_status', 'PENDING'),
            'payout_item_id' => data_get($payload, 'items.0.payout_item_id'),
            'sender_item_id' => data_get($payload, 'items.0.sender_item_id'),
            'raw' => $payload,
        ];
    }

    public function getPayout(string $batchId): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('PayPal payouts are not configured.');
        }

        $response = Http::acceptJson()
            ->withToken($this->createAccessToken())
            ->timeout(30)
            ->get($this->baseUrl() . '/v1/payments/payouts/' . $batchId, [
                'fields' => 'items',
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to fetch PayPal payout details: ' . $response->body());
        }

        $payload = $response->json();

        return [
            'batch_id' => data_get($payload, 'batch_header.payout_batch_id', $batchId),
            'batch_status' => data_get($payload, 'batch_header.batch_status'),
            'payout_item_id' => data_get($payload, 'items.0.payout_item_id'),
            'transaction_status' => data_get($payload, 'items.0.transaction_status'),
            'receiver' => data_get($payload, 'items.0.payout_item.receiver'),
            'raw' => $payload,
        ];
    }

    protected function createAccessToken(): string
    {
        $response = Http::asForm()
            ->acceptJson()
            ->withBasicAuth(
                config('services.paypal.client_id'),
                config('services.paypal.client_secret')
            )
            ->timeout(30)
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to authenticate with PayPal: ' . $response->body());
        }

        $accessToken = $response->json('access_token');

        if (! $accessToken) {
            throw new RuntimeException('PayPal did not return an access token.');
        }

        return $accessToken;
    }

    protected function baseUrl(): string
    {
        return rtrim(config('services.paypal.base_url', 'https://api-m.sandbox.paypal.com'), '/');
    }
}
