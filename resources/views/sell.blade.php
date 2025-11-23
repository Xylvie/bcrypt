@extends('layouts.app')


@section('content')

    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold">Sell {{ $coin['name'] ?? 'Unknown' }}</h1>
        <div class="flex items-center space-x-4">
            <img src="{{ $coin['image']['large'] ?? 'Coin Image' }}" alt="{{ $coin['name'] }}" class="w-16 h-16">
            <div>
                <h1 class="text-3xl font-bold">{{ $coin['name'] }} ({{ strtoupper($coin['symbol']) }})</h1>
                <p class="text-xl font-semibold">${{ number_format($coin['market_data']['current_price']['usd'], 2) }}</p>
                <p class="{{ $coin['market_data']['price_change_percentage_24h'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            24h: {{ number_format($coin['market_data']['price_change_percentage_24h'], 2) }}%
                </p>
            </div>
        </div>
        <form action="{{ route('sell.checkout') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <input type="text" hidden name="coin_name" value="{{ $coin['name'] }}">

                <div 
                    x-data="{
                        coin: 0,
                        price: {{ $coin['market_data']['current_price']['usd'] }},
                        get usd() {
                            return this.coin > 0 ? (this.coin * this.price).toFixed(2) : 0;
                        }
                    }"
                    class="mt-6 space-y-4"
                >
                    <label class="block mb-2 font-semibold">Amount to Sell ({{ strtoupper($coin['symbol']) }}):</label>
                    <input 
                        type="number"
                        step="0.00000001"
                        x-model="coin"
                        class="w-full p-2 text-gray-800 border rounded"
                        placeholder="Enter amount of coins"
                        name="amount_coin"
                    />

                    <p class="text-lg">
                        You will receive: 
                        <span class="font-bold" x-text="usd"></span> USD
                    </p>
                </div>

            </div>
            <button type="submit" class="px-5 py-2 text-xl font-bold bg-green-500 rounded-md shadow-md hover:text-gray-700 hover:bg-green-400">
                Proceed to Sell
            </button>
        </form>
    </div>
@endsection