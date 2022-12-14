<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coinFrom',
        'coinTo',
        'amountFrom',
        'amountTo',
        'rate',
        'commission',
        'status',
        'hash',
        'type',
        'comment'
    ];

    /**
     * > The `user()` function returns the user that owns the post
     * 
     * @return A collection of all the comments that belong to the user.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * > This function returns the coinFrom relationship
     * 
     * @return The coinFrom method returns the coinFrom relationship.
     */
    public function coinFrom()
    {
        return $this->belongsTo('App\Models\Coin', 'coinFrom');
    }

    /**
     * > This function returns the coinTo relationship
     * 
     * @return The coinTo() method returns the coinTo relationship.
     */
    public function coinTo()
    {
        return $this->belongsTo('App\Models\Coin', 'coinTo');
    }
}
