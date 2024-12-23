<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    // 1 次卡 2 时长卡 3 通卡 4 储值卡 5 项目 6 商品 7 快速消费

    const TYPE_TIMES_CARD = 1;
    const TYPE_DURATION_CARD = 2;
    const TYPE_GENERAL_CARD = 3;
    const TYPE_RECHARGE_CARD = 4;
    const TYPE_SERVICE = 5;
    const TYPE_PRODUCT = 6;
    const TYPE_FAST_CONSUME = 7;

    const TYPE_MAP = [
        self::TYPE_TIMES_CARD => '次卡',
        self::TYPE_DURATION_CARD => '时长卡',
        self::TYPE_GENERAL_CARD => '通卡',
        self::TYPE_RECHARGE_CARD => '储值卡',
        self::TYPE_SERVICE => '项目',
        self::TYPE_PRODUCT => '商品',
        self::TYPE_FAST_CONSUME => '快速消费',
    ];

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
        'level_deduct',
        'times_card_deduct',
        'duration_card_deduct',
        'deduct_desc',
        'use_card_id',
        'real_amount',
    ];

    protected $appends = ['type_name'];

    public function typeName(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => self::TYPE_MAP[$attributes['type']],
        );
    }
}
