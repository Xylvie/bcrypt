<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\Http;
use Illuminate\Support\facades\Cache;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $purchase = $request->validate([
            'amount_usd' => 'required|numeric|min:1',
            'coin_name' => 'required|string',
        ]);

        return view('checkout', compact('purchase'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         // Use cache key properly
        $cacheKeyCoin = "coin_{$id}";

        $coin = Cache::get($cacheKeyCoin, null);

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

        // If coin is still null, show a friendly message
        if (!$coin) {
            return view('buy', [
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
            ]);
        }

        return view('sell', compact('coin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
