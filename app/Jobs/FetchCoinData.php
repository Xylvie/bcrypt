<?php

namespace App\Jobs;

use App\Services\CoinGeckoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCoinData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(CoinGeckoService $service)
    {
        // Refresh top markets and store into cached_coins table
        $service->refreshAndStoreMarkets(250);
    }
}
