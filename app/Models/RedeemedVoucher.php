<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RedeemedVoucher extends Model
{
    protected $fillable = ['voucher_id', 'user_id', 'redeemed_at'];

    protected $casts = [
        'redeemed_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Relasi dengan model Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getRedeemedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}