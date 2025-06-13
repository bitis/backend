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

    public function name(): string
    {
        return match ($this) {
            CommissionConfigurableType::Product => '商品项目',
            CommissionConfigurableType::FastConsume => '快速消费',
            CommissionConfigurableType::OpenCard => '会员办卡',
            CommissionConfigurableType::FastStored => '会员储值',
        };
    }
}
