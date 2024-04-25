<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStaff extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'order_id',
        'staff_id',
        'order_id',
        'product_id',
        'product_name',
        'product_type',
        'number',
        'intro',
        'performance',
        'commission',
        'remark'
    ];

    // 1 次卡 2 时长卡 3 储值卡 4 服务 5 商品 6 未记录商品

    const TYPE_TIMES_CARD = 1;
    const TYPE_DURATION_CARD = 2;
    const TYPE_STORING_CARD = 3;
    const TYPE_SERVICE = 4;
    const TYPE_PRODUCT = 5;
    const TYPE_NOT_RECORD = 6;

    /**
     * @param array $staffs
     * @param OrderProduct $_order_product
     * @return void
     */
    public static function write(array $staffs, OrderProduct $_order_product): void
    {
        foreach ($staffs as $staff) {
            OrderStaff::create([
                'order_id' => $_order_product->order_id,
                'staff_id' => $staff['id'],
                'product_id' => $_order_product->product_id,
                'product_name' => $_order_product->product_name,
                'number' => $_order_product->number,
                'product_type' => $_order_product->type == OrderProduct::TYPE_PRODUCT ? OrderStaff::TYPE_PRODUCT : OrderStaff::TYPE_SERVICE,
                'intro' => '消费',
                'performance' => $staff['performance'],
                'commission' => $staff['commission'],
            ]);
        }
    }
}
