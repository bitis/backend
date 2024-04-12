<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum CommissionConfigurableType: int
{
    use EnumArray;

    case Product = 1;
    case FastConsume = 2;
    case OpenCard = 3;
    case FastStored = 4;
    case FastTimes = 5;

    public function name(): string
    {
        return match ($this) {
            CommissionConfigurableType::Product => '商品',
            CommissionConfigurableType::FastConsume => '快速消费',
            CommissionConfigurableType::OpenCard => '办卡',
            CommissionConfigurableType::FastStored => '快速储值',
            CommissionConfigurableType::FastTimes => '快速冲次',
        };
    }
}
