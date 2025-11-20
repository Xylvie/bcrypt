<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Coin;

class CoinGeckoService
{
    protected $base = 'https://api.coingecko.com/api/v3';

    /** Fetch market data (top coins) with caching */
    public function getMarkets(int $perPage = 100, int $page = 1, string $vs_currency = 'usd')
    {
        $cacheKey = "coingecko_markets_{$vs_currency}_{$perPage}_{$page}";

        return Cache::remember($cacheKey, 60, function () use ($perPage, $page, $vs_currency) {
            $resp = Http::withoutVerifying()->get("{$this->base}/coins/markets", [
                'vs_currency' => $vs_currency,
                'order' => 'market_cap_desc',
                'per_page' => $perPage,
                'page' => $page,
                'sparkline' => 'false',
            ]);

            return $resp->successful() ? $resp->json() : [];
        });
    }

    /** Fetch single coin detail (and cache to DB as extra) */
    public function getCoin(string $id)
    {
        $cacheKey = "coingecko_coin_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $resp = Http::get("{$this->base}/coins/{$id}", [
                'localization' => 'false',
                'tickers' => 'false',
                'market_data' => 'true',
                'community_data' => 'false',
                'developer_data' => 'false',
                'sparkline' => 'false',
            ]);

            return $resp->successful() ? $resp->json() : null;
        });
    }

    /** Helper to bulk refresh top coins and store in DB (used by job) */
    public function refreshAndStoreMarkets(int $perPage = 250)
    {
        $page = 1;
        $all = [];

        do {
            $markets = Http::get("{$this->base}/coins/markets", [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => $perPage,
                'page' => $page,
                'sparkline' => 'false',
            ])->json();

            if (empty($markets)) break;

            foreach ($markets as $coin) {
                $coingeckoId = $coin['id'];
                Coin::updateOrCreate(
                    ['coingecko_id' => $coingeckoId],
                    ['data' => $coin, 'fetched_at' => now()]
                );
            }

            $all = array_merge($all, $markets);
            $page++;
        } while (count($markets) === $perPage && $page < 5); // avoid too many pages

        return $all;
    }
}
