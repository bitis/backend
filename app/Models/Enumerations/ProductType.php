<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum ProductType: int
{
    use EnumArray;

    case Service = 0;
    case Product = 1;

    public function name(): string
    {
        return match ($this) {
            ProductType::Service => '服务',
            ProductType::Product => '商品',
        };
    }
}
