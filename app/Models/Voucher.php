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
        'merchant_id',
        'created_date',
        'expiration_date',
        'status',
        'sent_to',
        'sent_status',
        'sent_at',
        'redeemed_by',
        'redeemed_at',
    ];

    // Cast kolom tanggal sebagai datetime
    protected $casts = [
        'created_date' => 'datetime',
        'expiration_date' => 'datetime',
        'sent_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function redeemedVoucher()
    {
        return $this->hasOne(RedeemedVoucher::class, 'voucher_id', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}