<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantBalance extends Model
{
    protected $fillable = [
        'merchant_id',
        'year',
        'month',
        'used_balance',
        'remaining_balance',
    ];

    // Relasi dengan model User
    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}