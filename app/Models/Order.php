<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'member_id',
        'store_id',
        'order_number',
        'type',
        'intro',
        'total_amount',
        'deduct_amount',
        'real_amount',
        'pay_amount',
        'payment_type',
        'operator_id',
        'refund',
        'refund_at',
        'remark'
    ];

    const TYPE_OPEN = 1; // 开卡
    const TYPE_FAST_STORED = 1; // 快速充值
    const TYPE_FAST_TIMES = 1; // 快速充次
    const TYPE_CONSUME_FAST = 2; // 快速消费

    /**
     * 生成22位订单号
     *
     * @param int $store_id
     * @return string
     */
    public static function generateNumber(int $store_id): string
    {
        $date = date('ymdHis');
        return sprintf("%s%06d%s", $date, $store_id, rand(1000, 9999));
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(OrderStaff::class);
    }
}
