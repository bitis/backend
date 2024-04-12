<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = ['store_id', 'member_id', 'order_id', 'type', 'amount', 'remark', 'after', 'operator_id'];

    protected $hidden = ['deleted_at'];

    protected $appends = ['type_text'];

    const TYPE_RECHARGE = 1; // 充值
    const TYPE_PAY = 2; // 支付
    const TYPE_REFUND = 3; // 退款
    const TYPE_EXPAND = 4; // 手动增加
    const TYPE_REDUCE = 5; // 手动减少

    const TYPE_MAP =[
        self::TYPE_RECHARGE => '充值',
        self::TYPE_PAY => '支付',
        self::TYPE_REFUND => '退款',
        self::TYPE_EXPAND => '手动增加',
        self::TYPE_REDUCE => '手动减少',
    ];

    public function getTypeTextAttribute(): string
    {
        return self::TYPE_MAP[$this->type];
    }
}
