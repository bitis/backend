<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'title',
        'content',
        'show_at',
        'top',
        'sort_num',
        'is_show',
    ];
}
