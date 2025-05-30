<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'is_new',
        'balance',
        'consume_switch'
    ];
}
