<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'member_id',
        'member_card_id',
        'store_id',
        'type',
        'product_id',
        'value',
        'after',
        'order_id',
        'refund',
        'old_valid_time',
        'new_valid_time',
        'operator_id',
        'remark',
    ];

    const TYPE_INCOME = 1; // 办卡
    const TYPE_CONSUME = 2; // 消费
    const TYPE_REFUND = 3; // 退款
    const TYPE_EXPAND = 4; // 手动增加
    const TYPE_REDUCE = 5; // 手动减少
    const TYPE_CHANGE_VALID_TIME = 6; // 修改有效期
}
