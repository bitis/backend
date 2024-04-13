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
}
