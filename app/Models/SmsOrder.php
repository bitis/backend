<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsOrder extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'package_id',
        'name',
        'order_no',
        'number',
        'price',
        'payment_channel',
        'paid_at',
        'refunded_at'
    ];

    public function paid(): void
    {
        $smsConfig = SmsConfig::where('store_id', $this->store_id)->first();
        $smsConfig->balance = $smsConfig->balance + $this->number;
        $smsConfig->save();
        SmsLog::create([
            'store_id' => $this->store_id,
            'remark' => '购买短信套餐：' . $this->name,
            'type' => SmsLog::TYPE_ORDER,
            'order_id' => $this->id,
            'number' => $this->number,
            'balance' => $smsConfig->balance
        ]);
    }
}
