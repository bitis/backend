<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum SpecType: int
{
    use EnumArray;

    case Single = 1;
    case Multi = 2;

    public function name(): string
    {
        return match ($this) {
            SpecType::Single => '单规格',
            SpecType::Multi => '商品',
        };
    }
}
