<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'exchange_rate',
        'fee',
        'min_amount',
        'max_amount',
        'is_active',
        'wallet',
        'reserve',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * > The `transactions()` function returns all the transactions that belong to the coin
     * 
     * @return A collection of all the transactions that belong to the coin.
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }
}
