<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlists;
use Illuminate\Support\Facades\Auth;

class WatchlistsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $list = Watchlists::where('user_id', $user->id)->pluck('coingecko_id')->toArray();
        return view('watchlists.index', ['coins' => $list]);
    }

    public function store(Request $request)
    {
        $request->validate(['coingecko_id' => 'required|string']);
        $user = Auth::user();

        $watch = Watchlists::updateOrCreate([
            'user_id' => $user->id,
            'coingecko_id' => $request->coingecko_id
        ]);

        return response()->json(['success' => true, 'data' => $watch]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $deleted = Watchlists::where('user_id', $user->id)->where('coingecko_id', $id)->delete();
        return response()->json(['success' => $deleted > 0]);
    }
}
