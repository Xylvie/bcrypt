<?php

use App\Http\Controllers\BuyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\WatchlistsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CryptoController::class, 'index'])->name('home');
Route::get('/coins/{id}', [CryptoController::class, 'show'])->name('coins.show');
Route::get('/search', [CoinController::class, 'search'])->name('coins.search');
Route::get('/market', [CryptoController::class, 'index2'])->name('market');

Route::middleware('auth')->group(function () {
    Route::get('/buy/{id}', [BuyController::class, 'show'])->name('buy');
    Route::POST('/checkout', [BuyController::class, 'create'])->name('checkout');
    Route::get('/sell/{id}', [App\Http\Controllers\SellController::class, 'show'])->name('sell');
    Route::post('/sell/checkout', [App\Http\Controllers\SellController::class, 'create'])->name('sell.checkout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/watchlist', [WatchlistsController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistsController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{id}', [WatchlistsController::class, 'destroy'])->name('watchlist.destroy');
});
require __DIR__.'/auth.php';
