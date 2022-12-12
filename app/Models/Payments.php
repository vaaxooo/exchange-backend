<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'card_number',
        'card_expiration',
        'card_cvv',
        'wallet',
        'status',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
