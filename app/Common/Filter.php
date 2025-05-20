<?php

namespace App\Common;

class Filter
{
    public static function birthday($val): string
    {
        $day = date('j');
        $month = date('m');

        return match ($val) {
            1 => "month(birthday) = $month and day(birthday) = $day",
            2 => "month(birthday) = $month and day(birthday) between $day  and " . ($day + 7),
            3 => "month(birthday) = $month"
        };
    }
}
