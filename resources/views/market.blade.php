@extends('layouts.app')

@section('content')

@if ($coins && $page && $hasMore)



<section id="All Coins">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">All Coins</h1>
        <input type="text" id="search" placeholder="Search coins..." 
               class="w-1/3 px-4 py-2 text-black border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table Header -->
    <div class="hidden w-full px-5 pb-2 mb-2 font-semibold text-gray-200 border-b border-gray-300 md:flex md:justify-center">
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
            <div class="w-full gap-5 text-blue-500 m:flex m:justify-center md:w-2/12 hover:underline">
                <a href="{{ route('coins.show', $coin['id']) }}">View</a>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="flex justify-center mt-5">
        <nav aria-label="Page navigation example">
            <ul class="flex gap-5 -space-x-px text-sm">
                @if ($page > 1)
                    <li>   
                        <a href="?page={{ $page - 1 }}" class="box-border flex items-center justify-center px-3 text-sm font-medium border text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-s-base h-9 focus:outline-none">Previous</a>
                    </li>
                @endif

                <li>   
                    <a href="#" class="box-border flex items-center justify-center px-3 text-sm font-medium border text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-s-base h-9 focus:outline-none">{{ $page }}</a>
                </li>

                @if ($hasMore)
                    <li>
                        <a href="?page={{ $page + 1 }}" class="box-border flex items-center justify-center px-3 text-sm font-medium border text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-e-base h-9 focus:outline-none">Next</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

</section>

@else

<div class="flex items-center justify-center w-full h-screen">
    <h1 class="text-3xl font-bold text-center text-bold">Coins are being rendered please refresh after a minute</h1>
</div>

@endif

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
