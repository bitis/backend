<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreStat extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'new_member',
        'new_order', // 新增订单
        'consumer_member', // 消费会员数
        'sale_card_amount', // 售卡金额
        'use_card_amount', // 使用储值卡销售金额
        'use_card_times', // 使用储值卡销售次数
        'use_money_amount', // 使用现金销售金额
        'cost_amount', // 商品成本
        'staff_sale_amount', // 员工业绩
        'staff_bonus_amount',// 员工提成
        'profit_amount', // 利润
        'date',
        'month',
        'year'
    ];
}
