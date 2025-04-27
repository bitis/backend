<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'contents',
        'images',
        'reply',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
