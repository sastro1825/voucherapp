<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_name',
        'value',
        'created_date',
        'expiration_date',
        'status',
        'sent_to',
        'sent_status',
        'sent_at',
        'redeemed_by',
        'redeemed_at',
    ];

    protected $dates = [
        'created_date',
        'expiration_date',
        'sent_at',
        'redeemed_at',
        'created_at',
        'updated_at',
    ];

    public function redeemedVoucher()
    {
        return $this->hasOne(RedeemedVoucher::class, 'voucher_id', 'id');
    }
}