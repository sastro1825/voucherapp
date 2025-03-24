<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemedVoucher extends Model
{
    protected $fillable = ['voucher_id', 'user_id', 'redeemed_at'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}