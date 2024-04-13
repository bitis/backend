<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    // 1 次卡 2 时长卡 3 储值卡 4 项目 5 商品

    const TYPE_TIMES_CARD = 1;
    const TYPE_DURATION_CARD = 2;
    const TYPE_RECHARGE_CARD = 3;
    const TYPE_SERVICE = 4;
    const TYPE_PRODUCT = 5;
    const TYPE_FAST_CONSUME = 6;

    protected $fillable = [
        'type',
        'order_id',
        'product_id',
        'product_sku_id',
        'product_name',
        'product_image',
        'number',
        'price',
        'total_amount',
        'deduct_amount',
        'deduct_desc',
        'use_card_id',
        'real_amount',
    ];
}
