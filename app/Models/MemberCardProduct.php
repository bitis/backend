<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCardProduct extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

    protected $fillable = [

    ];
}
