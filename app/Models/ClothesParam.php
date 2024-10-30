<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothesParam extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'store_id',
        'type',
        'name',
        'code',
        'image',
    ];

    const TYPE_MAP = [
        1 => '颜色',
        2 => '暇疵',
    ];
}
