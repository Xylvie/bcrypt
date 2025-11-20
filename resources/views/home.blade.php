@extends('layouts.app')

@section('content')

<section id="hero">
    <div class="container px-4 py-6 mx-auto">

        <div>
            <div class="container mx-auto text-center">
                <h1 class="mb-4 text-4xl font-bold">Track Cryptocurrency Prices in Real-Time</h1>
                <p class="mb-6 text-gray-300">Get live updates for the top cryptocurrencies directly from CoinGecko</p>
                <a href="#coins-list" class="px-6 py-3 text-white bg-blue-500 rounded hover:bg-blue-600">View Coins</a>
            </div>
        </div>
    </div>
</section>

<section id="Top Movers">
    <div class="container py-8 mx-auto">
    <h2 class="mb-4 text-2xl font-bold">Top Movers</h2>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        @foreach(array_slice($coins, 0, 8) as $coin)
        <div class="p-4 rounded shadow-lg bg-slate-700">
            <div class="flex items-center justify-between">
                <img src="{{ $coin['image'] ?? 'Coin Image' }}" class="w-8 h-8 mb-2">
                <h3 class="font-semibold">{{ $coin['name'] ?? 'Uknown' }} ({{ strtoupper($coin['symbol'] ?? 'Coin Symbol') }})</h3>
            </div>
            <p>${{ number_format($coin['current_price'] ?? '', 2) }}</p>
            <p class="{{ $coin['price_change_percentage_24h'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ number_format($coin['price_change_percentage_24h'], 2) }}%
            </p>
        </div>
        @endforeach
    </div>
</div>

</section>

<section id="Quick Stats" class="mb-10">
    <div class="py-6 rounded bg-slate-700">
    <div class="container flex justify-around mx-auto text-center">
        <div>
            <h3 class="font-bold">Total Coins</h3>
            <p>{{ $totalCoins }}</p>
        </div>
        <div>
            <h3 class="font-bold">Market Cap</h3>
            <p>${{ number_format($totalMarketCap) }}</p>
        </div>
        <div>
            <h3 class="font-bold">BTC Dominance</h3>
            <p>{{ $btcDominance }}%</p>
        </div>
    </div>
</div>

</section>

<section id="All Coins">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Top 100 Coins</h1>
        <input type="text" id="search" placeholder="Search coins..." 
               class="w-1/3 px-4 py-2 text-black border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table Header -->
    <div class="hidden px-5 pb-2 mb-2 font-semibold text-gray-200 border-b border-gray-300 md:flex">
        <div class="w-1/12">#</div>
        <div class="w-3/12">Coin</div>
        <div class="w-2/12">Price</div>
        <div class="w-2/12">Market Cap</div>
        <div class="w-2/12">24h Change</div>
        <div class="w-2/12">Action</div>
    </div>

    <!-- Coins List -->
    <div id="coins-list" class="w-full max-h-screen overflow-y-scroll no-scrollbar">
        @foreach($coins as $index => $coin)
        <div class="flex flex-col items-center py-2 border-b border-gray-200 md:flex-row md:items-start">
            <div class="w-full mb-5 text-start md:w-1/12 md:text-left">{{ $index + 1 }}</div>
            <div class="flex items-center w-full space-x-2 md:w-3/12">
                <img src="{{ $coin['image'] }}" alt="{{ $coin['name'] }}" class="w-6 h-6">
                <span>{{ $coin['name'] }} ({{ strtoupper($coin['symbol']) }})</span>
            </div>
            <div class="w-full md:w-2/12">${{ number_format($coin['current_price'], 2) }}</div>
            <div class="w-full md:w-2/12">${{ number_format($coin['market_cap']) }}</div>
            <div class="w-full md:w-2/12 {{ $coin['price_change_percentage_24h'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ number_format($coin['price_change_percentage_24h'], 2) }}%
            </div>
            <div class="w-full text-blue-500 md:w-2/12 hover:underline">
                <a href="{{ route('coins.show', $coin['id']) }}">View</a>
            </div>
        </div>
        @endforeach
    </div>
</section>

<script>
// Simple instant filtering
const searchInput = document.getElementById('search');
searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#coins-list > div').forEach(coinRow => {
        const name = coinRow.querySelector('div:nth-child(2) span').textContent.toLowerCase();
        coinRow.style.display = name.includes(query) ? 'flex' : 'none';
    });
});
</script>
@endsection
