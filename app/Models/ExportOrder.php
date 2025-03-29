<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportOrder extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'order_no',
        'price',
        'original_price',
        'payment_channel',
        'payment_no',
        'paid_at',
        'export_at',
        'status',
        'file'
    ];

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;
    const STATUS_CANCEL = 2;
    const STATUS_REFUND = 3;
    const STATUS_EXPORT = 4;

    const statusMap = [
        self::STATUS_UNPAID => '未支付',
        self::STATUS_PAID => '已支付',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_REFUND => '已退款',
        self::STATUS_EXPORT => '已导出'
    ];

    /**
     * 生成22位订单号
     *
     * @return string
     */
    public static function generateNumber(): string
    {
        return sprintf("%s%s", date('ymdHis'), rand(100, 999));
    }

    public static function paid($order_no, $payment_channel, $payment_no)
    {
        $order = self::where('order_no', $order_no)->first();
        if (empty($order)) return false;

        $order->status = self::STATUS_PAID;
        $order->payment_channel = $payment_channel;
        $order->payment_no = $payment_no;
        $order->paid_at = now();
        $order->save();

        return true;
    }
}
