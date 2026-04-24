<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class PayPalCheckoutService
{
    public function isConfigured(): bool
    {
        return filled(config('services.paypal.client_id'))
            && filled(config('services.paypal.client_secret'));
    }

    public function createOrder(float $amount, string $currency, string $returnUrl, string $cancelUrl, string $description): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('PayPal checkout is not configured.');
        }

        $response = Http::asJson()
            ->acceptJson()
            ->withToken($this->createAccessToken())
            ->timeout(30)
            ->post($this->baseUrl() . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'description' => $description,
                    'amount' => [
                        'currency_code' => strtoupper($currency),
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to create PayPal order: ' . $response->body());
        }

        $payload = $response->json();
        $approveUrl = collect($payload['links'] ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approveUrl) {
            throw new RuntimeException('PayPal did not return an approval URL.');
        }

        return [
            'order_id' => $payload['id'] ?? null,
            'status' => $payload['status'] ?? null,
            'approve_url' => $approveUrl,
            'raw' => $payload,
        ];
    }

    public function captureOrder(string $orderId): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('PayPal checkout is not configured.');
        }

        $response = Http::asJson()
            ->acceptJson()
            ->withToken($this->createAccessToken())
            ->withHeaders([
                'PayPal-Request-Id' => (string) Str::uuid(),
            ])
            ->timeout(30)
            ->post($this->baseUrl() . '/v2/checkout/orders/' . $orderId . '/capture', new \stdClass());

        if ($response->failed()) {
            throw new RuntimeException('Unable to capture PayPal order: ' . $response->body());
        }

        $payload = $response->json();

        return [
            'status' => $payload['status'] ?? null,
            'capture_id' => data_get($payload, 'purchase_units.0.payments.captures.0.id'),
            'capture_status' => data_get($payload, 'purchase_units.0.payments.captures.0.status'),
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
