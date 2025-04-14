<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'openid',
        'unionid',
        'official_openid',
        'token',
        'coin'
    ];

    protected static function booted(): void
    {
        static::created(function (MiniUser $user) {
            MiniCoinLog::create([
                'user_id' => $user->id,
                'type' => MiniCoinLog::INCREASE,
                'remark' => '注册赠送',
                'before' => 0,
                'value' => 50,
                'after' => 50
            ]);
        });
    }
}
