<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = ['store_id', 'member_id', 'order_id', 'type', 'amount', 'remark', 'after'];

    protected $hidden = ['deleted_at'];

    const TYPE_RECHARGE = 1; // 充值
    const TYPE_PAY = 2; // 支付
    const TYPE_REFUND = 3; // 退款
    const TYPE_EXPAND = 4; // 手动增加
    const TYPE_REDUCE = 5; // 手动减少
}
