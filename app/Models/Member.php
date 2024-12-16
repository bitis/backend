<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'pid',
        'store_id',
        'name',
        'avatar',
        'gender',
        'number',
        'mobile',
        'birthday',
        'level_id',
        'balance',
        'integral',
        'total_consume_amount',
        'total_consume_times',
        'first_consume_at',
        'last_consume_at',
        'openid',
        'unionid',
        'remark'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Grade::class)->select(['id', 'name']);
    }
}
