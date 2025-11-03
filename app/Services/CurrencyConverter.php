<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class CurrencyConverter
{
    private string $apiKey;
    private string $baseUrl = 'https://free.currconv.com/api/v7';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convert(float $amount, string $from, string $to): ?float
    {
        try {
            $q = "{$from}_{$to}";

            $response = Http::get($this->baseUrl . '/convert', [
                'q' => $q,
                'compact' => 'y',
                'apiKey' => $this->apiKey,
            ]);

            $response->throw();

            $result = $response->json();

            if (isset($result[$q]['val'])) {
                return round($result[$q]['val'] * $amount, 2);
            }

            return null;
        } catch (RequestException $e) {
            return null;
        }
    }
}
