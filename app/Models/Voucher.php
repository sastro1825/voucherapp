<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'id', 'company_name', 'value', 'created_date', 'expiration_date', 'status', 'redeemed_by', 'redeemed_at', 'send_status'
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    public function redeemedVoucher()
    {
        return $this->hasOne(RedeemedVoucher::class, 'voucher_id', 'id');
    }
}