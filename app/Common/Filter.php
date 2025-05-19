<?php

namespace App\Common;

class Filter
{
    public static function birthday($val): array
    {
        $day = date('j');
        $month = date('m');

        return match ($val) {
            1 => ["MONTH(birthday) = $month and DAY(birthday) = $day"],
            2 => ['MONTH(birthday) = $month and DAY(birthday) BETWEEN "' . $day . '" and "' . ($day + 7) . '"'],
        };
    }
}
