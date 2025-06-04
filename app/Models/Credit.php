<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'price',
        'send',
        'self'
    ];
}
