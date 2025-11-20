<?php

namespace App\Http\Controllers;

use App\Services\CoinGeckoService;
use App\Models\Coin;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    protected $cg;

    public function __construct(CoinGeckoService $cg)
    {
        $this->cg = $cg;
    }

    public function index(Request $request)
    {
        $coins = app(CoinGeckoService::class)->getMarkets(50);
    return view('home', compact('coins'));
    }

    public function show($id)
    {
        $coin = $this->cg->getCoin($id);

        if (!$coin) abort(404);

        return view('coins.show', compact('coin'));
    }

    // Optional search endpoint (AJAX)
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $markets = $this->cg->getMarkets(250, 1);

        $filtered = collect($markets)
            ->filter(fn($c) => str_contains(strtolower($c['name']), strtolower($q)) || str_contains(strtolower($c['symbol']), strtolower($q)))
            ->take(100)
            ->values();

        return response()->json($filtered);
    }
}
