<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum CommissionConfigurableType: int
{
    use EnumArray;

    case Product = 1;
    case Service = 2;
    case FastConsume = 3;
    case OpenCard = 4;
    case Stored = 5;

    public function name(): string
    {
        return match ($this) {
            CommissionConfigurableType::Product => '实物商品',
            CommissionConfigurableType::Service => '服务项目',
            CommissionConfigurableType::FastConsume => '快速消费',
            CommissionConfigurableType::OpenCard => '会员办卡',
            CommissionConfigurableType::Stored => '会员储值',
        };
    }
}
