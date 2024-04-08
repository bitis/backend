<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum CommissionConfigurableType: int
{
    use EnumArray;

    case Service = 1;
    case Product = 2;
    case Fast = 3;
    case Card = 4;
    case StoredValueCard = 5;

    public function name(): string
    {
        return match ($this) {
            CommissionConfigurableType::Service => '服务',
            CommissionConfigurableType::Product => '商品',
            CommissionConfigurableType::Fast => '快速消费',
            CommissionConfigurableType::Card => '办卡',
            CommissionConfigurableType::StoredValueCard => '储值',
        };
    }
}
