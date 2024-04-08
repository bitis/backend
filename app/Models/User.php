<?php

namespace App\Models;

use App\Models\Enumerations\UserStatus;
use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, DefaultDatetimeFormat, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'mobile',
        'store_id',
        'mobile_verified_at',
        'password',
        'openid',
        'unionid',
        'is_admin',
        'status',
        'job_id',
        'remark',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => $value ?: '',
        );
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    protected static function booted()
    {
        static::updating(function ($user) {

            // 封禁用户
            if ($user->status == UserStatus::Disable->value) {
                $user->api_token = '';
            }
        });
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
