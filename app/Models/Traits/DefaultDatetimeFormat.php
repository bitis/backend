<?php

namespace App\Models\Traits;

use DateTimeInterface;

trait DefaultDatetimeFormat
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
