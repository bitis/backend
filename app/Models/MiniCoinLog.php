<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiniCoinLog extends Model
{
    use SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'user_id', 'type', 'remark', 'before', 'value', 'after'
    ];

    const DECREASE = 2;
    const INCREASE = 1;
}
