@extends('layouts.app')

@section('content')
<div class="container px-4 py-8 mx-auto">

    {{-- Header / Hero --}}
    <div class="flex flex-col items-center space-y-4 md:flex-row md:justify-between md:space-y-0">
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
        <a href="{{ route('home') }}" class="mt-2 text-blue-500 hover:underline md:mt-0">← Back to All Coins</a>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 gap-4 mt-8 md:grid-cols-3 lg:grid-cols-4">
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold">Market Cap</h2>
            <p>${{ number_format($coin['market_data']['market_cap']['usd']) }}</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold text-green-400">24h High</h2>
            <p>${{ number_format($coin['market_data']['high_24h']['usd']) }}</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold text-red-400">24h Low</h2>
            <p>${{ number_format($coin['market_data']['low_24h']['usd']) }}</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold">Volume</h2>
            <p>${{ number_format($coin['market_data']['total_volume']['usd']) }}</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold">Rank</h2>
            <p>#{{ $coin['market_cap_rank'] }}</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow dark:bg-gray-800">
            <h2 class="font-semibold">Circulating Supply</h2>
            <p>{{ number_format($coin['market_data']['circulating_supply']) }}</p>
        </div>
    </div>

    {{-- Price Chart Placeholder --}}
    <div class="mt-8">
        <h2 class="mb-4 text-2xl font-bold">Price Chart (7d)</h2>
        <canvas id="coinChart" class="w-full h-64 rounded shadow bg-gray-50 dark:bg-gray-900"></canvas>
    </div>

    {{-- About / Description --}}
    <div class="mt-8 space-y-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
            About {{ $coin['name'] ?? 'Unknown' }}
        </h2>

        <div class="max-w-full p-4 prose rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800 dark:prose-invert">
            {!! $coin['description']['en'] ?? '<p>No description available.</p>' !!}
        </div>

        @if(isset($coin['links']['homepage'][0]) && $coin['links']['homepage'][0])
        <div class="p-4 mt-2 rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800">
            <h3 class="mb-1 font-semibold text-gray-700 dark:text-gray-200">Official Website:</h3>
            <a href="{{ $coin['links']['homepage'][0] }}" target="_blank" class="text-blue-500 break-words hover:underline">
                {{ $coin['links']['homepage'][0] }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

{{-- Chart.js Script --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('coinChart').getContext('2d');
        const coinChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($coinChart['labels']) !!},
                datasets: [{
                    label: '{{ $coin['name'] }} Price (USD)',
                    data: {!! json_encode($coinChart['prices']) !!},
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { display: true },
                    y: { display: true }
                }
            }
        });
    </script>
@endsection
