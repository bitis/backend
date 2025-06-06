<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = [
        'name',
        'avatar',
        'industry_id',
        'province',
        'city',
        'area',
        'address',
        'contact_name',
        'contact_mobile',
        'contact_wechat',
        'official_account_qrcode',
        'forever',
        'expiration_date',
        'blocked',
        'block_reason',
        'images',
        'introduction'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }
}
