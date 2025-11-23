<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CryptoController extends Controller
{
    // Show market data
    public function index()
    {
        // Use cache if available
        $coins = Cache::get('coins');

        try {
            $response = Http::withoutVerifying()->get('https://api.coingecko.com/api/v3/coins/markets', [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 50,
                'page' => 1,
                'sparkline' => false
            ])->json();

            if ($response && count($response) > 0) {
                $coins = $response;
                Cache::put('coins', $coins, 60); // cache for 60 seconds
            }
        } catch (\Exception $e) {
            // Do nothing, fallback to cached data
        }

        if (!$coins) {
            $coins = []; // fallback empty array
        }

        $totalCoins = count($coins);
        $totalMarketCap = array_sum(array_column($coins, 'market_cap') ?? []);
        $btc = collect($coins)->firstWhere('id', 'bitcoin');
        $btcDominance = $btc && $totalMarketCap > 0 ? ($btc['market_cap'] / $totalMarketCap) * 100 : 0;

        return view('home', compact('coins', 'totalCoins', 'totalMarketCap', 'btcDominance'));
    }

     public function index2()
    {
        // Use cache if available
        $coins = Cache::get('coins');
        $page = request()->get('page', 1);

        try {
            $response = Http::withoutVerifying()->get('https://api.coingecko.com/api/v3/coins/markets', [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 100,
                'page' => $page,
                'sparkline' => false
            ])->json();

            if ($response && count($response) > 0) {
                $coins = $response;
                Cache::put('coins', $coins, 60); // cache for 60 seconds
            }
        } catch (\Exception $e) {
            // Do nothing, fallback to cached data
        }

        if (!$coins) {
            $coins = []; // fallback empty array
        }

        $hasMore = count($coins) == 100;

        return view('market', compact('coins', 'page', 'hasMore'));
    }

    // Show individual coin
    public function show($id)
    {
        // Use cache key properly
        $cacheKeyCoin = "coin_{$id}";
        $cacheKeyChart = "coin_chart_{$id}";

        $coin = Cache::get($cacheKeyCoin, null);
        $coinChart = Cache::get($cacheKeyChart,['labels' => [], 'prices' => []]);

        // Try to fetch coin data from API
        try {
            $response = Http::withoutVerifying()
                ->get("https://api.coingecko.com/api/v3/coins/{$id}")
                ->json();

            if (isset($response['id'])) {
                $coin = $response;
                Cache::put($cacheKeyCoin, $coin, 60); // cache for 60 seconds
            }
        } catch (\Exception $e) {
            // fallback to cached coin
        }

        try {
            $chartData = Http::withoutVerifying()
                ->get("https://api.coingecko.com/api/v3/coins/{$id}/market_chart", [
                    'vs_currency' => 'usd',
                    'days' => 7,
                ])->json();

            if (isset($chartData['prices'])) {
                $coinChart = [
                    'labels' => collect($chartData['prices'])->pluck(0),
                    'prices' => collect($chartData['prices'])->pluck(1),
                ];
                Cache::put($cacheKeyChart, $coinChart, 60);
            }
        } catch (\Exception $e) {
            // API failed, fallback to cached chart
        }

        // If coin is still null, show a friendly message
        if (!$coin) {
            return view('coins.show', [
                'coin' => [
                    'name' => 'Data unavailable',
                    'symbol' => $id,
                    'market_data' => [
                        'current_price' => ['usd' => 0],
                        'market_cap' => ['usd' => 0],
                        'high_24h' => ['usd' => 0],
                        'low_24h' => ['usd' => 0],
                    ],
                ],
                'coinChart' => $coinChart
            ]);
        }

        return view('coins.show', compact('coin', 'coinChart'));
    }
}
