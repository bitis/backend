<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiniSubscribe extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'type'
    ];
}
