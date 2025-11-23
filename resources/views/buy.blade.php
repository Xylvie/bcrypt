@extends('layouts.app')

@section('content')

 <section>
    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold">Buy {{ $coin['name'] ?? 'Unknown' }}</h1>

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
        
        <form action="{{ route('checkout') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <input type="text" hidden name="coin_name" value="{{ $coin['name'] }}">

                <div 
                    x-data="{
                        usd: 0,
                        price: {{ $coin['market_data']['current_price']['usd'] }},
                        get crypto() {
                            return this.usd > 0 ? (this.usd / this.price).toFixed(8) : 0;
                        }
                    }"
                    class="mt-6 space-y-4"
                >
                    <label class="block mb-2 font-semibold">Amount to Buy (USD):</label>
                    <input 
                        type="number" 
                        x-model="usd" 
                        class="w-full p-2 text-gray-800 border rounded"
                        placeholder="Enter amount in USD"
                        name="amount_usd"
                    />

                    <p class="text-lg">
                        You will get: 
                        <span class="font-bold" x-text="crypto"></span> 
                        <span class="uppercase">{{ $coin['symbol'] }}</span>
                    </p>
                </div>
            </div>
            <button type="submit" class="px-5 py-2 text-xl font-bold bg-blue-500 rounded-md shadow-md hover:text-gray-700 hover:bg-blue-400">
                Proceed to Buy
            </button>
        </form>


 </section>


@endsection