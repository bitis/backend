<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile', 'code', 'getaway', 'expiration_date', 'verified'
    ];

    protected $casts = [
        'verified' => 'boolean'
    ];

    /**
     * 验证码是否有效
     *
     * @param $mobile
     * @param $code
     * @return boolean
     */
    public static function verify($mobile, $code): bool
    {
        if (app()->environment('testing')) return true;

        $verification_code = VerificationCode::where('mobile', $mobile)
            ->where('code', $code)
            ->where('verified', false)
            ->where('expiration_date', '>', now())
            ->first();

        if (!$verification_code) return false;

        $verification_code->verified = true;
        $verification_code->save();

        return true;
    }
}
