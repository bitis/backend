<?php

namespace App\Models;

use App\Models\Enumerations\Status;
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
        'account',
        'mobile',
        'company_id',
        'password',
        'api_token',
        'status',
        'identity_id',
        'employee_id',
        'remark',
        'push_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => $value ?: Company::find($this->company_id)?->logo,
        );
    }

    protected static function booted()
    {
        static::updating(function ($user) {

            // 封禁用户
            if ($user->status == Status::Disable->value) {
                $user->api_token = '';
            }
        });
    }

    protected function getDefaultGuardName(): string { return 'api'; }
}
