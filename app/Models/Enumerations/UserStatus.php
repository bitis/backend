<?php

namespace App\Models\Enumerations;

use App\Models\Enumerations\Traits\EnumArray;

enum UserStatus: string
{
    use EnumArray;

    case Normal = '0';
    case Disable = '1';
    case Destroy = '2';

    public function name(): string
    {
        return match ($this) {
            UserStatus::Normal => '正常',
            UserStatus::Disable => '禁用',
            UserStatus::Destroy => '注销'
        };
    }
}
