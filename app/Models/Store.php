<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'expiration_date',
        'images',
        'introduction'
    ];

    protected $hidden = [
        'deleted_at'
    ];
    
    protected $casts = [
        'images' => 'array'
    ];
}
