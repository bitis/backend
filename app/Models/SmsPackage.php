<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPackage extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'name',
        'description',
        'number',
        'price',
        'unit_price',
        'original_price',
        'limit'
    ];
}
