<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisaProduct extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'name',
        'subtitle',
        'entranceImg',
        'seckillImg',
        'sellPrice',
        'purchasePrice',
        'stockStatus',
        'v_id',
        'activityId',
        'channelId',
        'stock',
        'goodsIntroduction',
        'purchaseNotes',
        'goodsTagOne',
        'goodsTagTwo',
        'price'
    ];

    const TYPE_VISA = 1;
    const TYPE_LENOVO = 2;
}
