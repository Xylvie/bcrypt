@extends('layouts.app')

@section('content')
    <div id="checkout-container">
        <div class="container flex flex-col items-center px-4 py-8 mx-auto">
            <h1 class="mb-6 text-3xl font-bold md:text-6xl">Checkout</h1>
            <p class="mb-4 md:text-3xl">You are about to purchase <strong>${{ number_format($purchase['amount_usd'], 2) }}</strong> worth of <strong>{{ $purchase['coin_name'] }}</strong>.</p>
            <div class="flex items-center gap-10">
                <button class="px-5 py-2 text-xl font-bold bg-green-500 rounded-md shadow-md hover:text-gray-700 hover:bg-green-400">
                    Confirm
                </button>
                <button class="px-5 py-2 text-xl font-bold bg-red-500 rounded-md shadow-md hover:text-gray-700 hover:bg-red-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endsection