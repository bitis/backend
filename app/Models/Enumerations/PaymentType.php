<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum PaymentType: int
{
    use EnumArray;

    case Wechat = 1;
    case Alipay = 2;
    case Cash = 3;
    case Other = 99;



    public function name(): string
    {
        return match ($this) {
            PaymentType::Wechat => '微信',
            PaymentType::Alipay => '支付宝',
            PaymentType::Cash => '现金',
            PaymentType::Other => '其他',
        };
    }
}
