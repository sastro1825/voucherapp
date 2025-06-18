<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected $casts = [
        'created_date' => 'datetime:Y-m-d H:i:s',
        'expiration_date' => 'datetime:Y-m-d H:i:s',
        'sent_at' => 'datetime:Y-m-d H:i:s',
        'redeemed_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function redeemedVoucher()
    {
        return $this->hasOne(RedeemedVoucher::class, 'voucher_id', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    // Accessor untuk memformat tanggal dengan timezone WIB
    public function getCreatedDateAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function getExpirationDateAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta');
    }

    public function getSentAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') : null;
    }

    public function getRedeemedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') : null;
    }
}