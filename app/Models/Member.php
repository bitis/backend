<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes, DefaultDatetimeFormat;

    protected $fillable = [
        'pid',
        'name',
        'avatar',
        'gender',
        'number',
        'mobile',
        'birthday',
        'level_id',
        'balance',
        'integral',
        'total_consumption_amount',
        'total_consumption_times',
        'first_consumption_at',
        'last_consumption_at',
        'openid'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function level(): HasOne
    {
        return $this->hasOne(Level::class);
    }
}
