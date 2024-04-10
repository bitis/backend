<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCard extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = [
        'member_id',
        'store_id',
        'type',
        'status',
        'card_id',
        'price',
        'valid_type',
        'valid_time',
        'remark',
        'commission_config',
        'status'
    ];

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
}
