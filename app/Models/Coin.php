<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = ['coingecko_id', 'data', 'fetched_at'];

    protected $casts = [
        'data' => 'array',
        'fetched_at' => 'datetime',
    ];
}
