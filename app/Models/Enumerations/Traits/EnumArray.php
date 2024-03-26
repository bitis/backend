<?php

namespace App\Models\Enumerations\Traits;

trait EnumArray
{
    public static function toArray(): array
    {
        $menuType = [];

        foreach (self::cases() as $type) {
            $menuType[] = [
                'id' => $type,
                'name' => $type->name()
            ];
        }

        return $menuType;
    }
}
