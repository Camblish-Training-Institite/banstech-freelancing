<?php

namespace App\Services\Payments;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CurrencyConversionService
{
    protected const PAYPAL_SUPPORTED_CURRENCIES = [
        'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS',
        'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'SGD',
        'SEK', 'CHF', 'THB', 'USD',
    ];

    protected const COUNTRY_TO_CURRENCY = [
        'AU' => 'AUD',
        'AUSTRALIA' => 'AUD',
        'BR' => 'BRL',
        'BRAZIL' => 'BRL',
        'CA' => 'CAD',
        'CANADA' => 'CAD',
        'CH' => 'CHF',
        'SWITZERLAND' => 'CHF',
        'CN' => 'CNY',
        'CHINA' => 'CNY',
        'CZ' => 'CZK',
        'CZECH REPUBLIC' => 'CZK',
        'DE' => 'EUR',
        'FR' => 'EUR',
        'ES' => 'EUR',
        'IT' => 'EUR',
        'NL' => 'EUR',
        'PT' => 'EUR',
        'IE' => 'EUR',
        'AT' => 'EUR',
        'BE' => 'EUR',
        'FI' => 'EUR',
        'GR' => 'EUR',
        'EUROPEAN UNION' => 'EUR',
        'HK' => 'HKD',
        'HONG KONG' => 'HKD',
        'HU' => 'HUF',
        'HUNGARY' => 'HUF',
        'IL' => 'ILS',
        'ISRAEL' => 'ILS',
        'JP' => 'JPY',
        'JAPAN' => 'JPY',
        'MX' => 'MXN',
        'MEXICO' => 'MXN',
        'MY' => 'MYR',
        'MALAYSIA' => 'MYR',
        'NO' => 'NOK',
        'NORWAY' => 'NOK',
        'NZ' => 'NZD',
        'NEW ZEALAND' => 'NZD',
        'PH' => 'PHP',
        'PHILIPPINES' => 'PHP',
        'PL' => 'PLN',
        'POLAND' => 'PLN',
        'SE' => 'SEK',
        'SWEDEN' => 'SEK',
        'SG' => 'SGD',
        'SINGAPORE' => 'SGD',
        'TH' => 'THB',
        'THAILAND' => 'THB',
        'TW' => 'TWD',
        'TAIWAN' => 'TWD',
        'GB' => 'GBP',
        'UK' => 'GBP',
        'UNITED KINGDOM' => 'GBP',
        'US' => 'USD',
        'UNITED STATES' => 'USD',
        'ZA' => 'ZAR',
        'SOUTH AFRICA' => 'ZAR',
    ];

    public function currencyContextForUser(?User $user): array
    {
        $country = strtoupper(trim((string) optional($user?->profile)->country));
        $localCurrency = self::COUNTRY_TO_CURRENCY[$country] ?? config('services.paypal.default_local_currency', 'USD');
        $paypalCurrency = in_array($localCurrency, self::PAYPAL_SUPPORTED_CURRENCIES, true)
            ? $localCurrency
            : config('services.paypal.fallback_currency', 'USD');

        return [
            'country' => $country ?: 'DEFAULT',
            'local_currency' => $localCurrency,
            'paypal_currency' => $paypalCurrency,
            'requires_conversion' => $localCurrency !== $paypalCurrency,
        ];
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): array
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($amount <= 0) {
            throw new RuntimeException('Amount to convert must be greater than zero.');
        }

        if ($fromCurrency === $toCurrency) {
            return [
                'source_amount' => round($amount, 2),
                'source_currency' => $fromCurrency,
                'target_amount' => round($amount, 2),
                'target_currency' => $toCurrency,
                'exchange_rate' => 1.0,
            ];
        }

        $baseUrl = rtrim(config('services.fx.base_url', 'https://api.frankfurter.dev'), '/');
        $response = Http::acceptJson()
            ->timeout(15)
            ->get($baseUrl . '/v2/rate/' . $fromCurrency . '/' . $toCurrency);

        if ($response->failed()) {
            throw new RuntimeException('Unable to fetch currency conversion rate.');
        }

        $rate = (float) $response->json('rate');

        if ($rate <= 0) {
            throw new RuntimeException('Currency conversion rate was invalid.');
        }

        return [
            'source_amount' => round($amount, 2),
            'source_currency' => $fromCurrency,
            'target_amount' => round($amount * $rate, 2),
            'target_currency' => $toCurrency,
            'exchange_rate' => $rate,
        ];
    }
}
