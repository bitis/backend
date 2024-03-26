<?php

namespace App\Http\Controllers;

use App\Common\Messages\VerificationCode;
use App\Models\User;
use App\Models\VerificationCode as VerificationCodeModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodeController extends Controller
{
    public function get(Request $request, EasySms $easySms): JsonResponse
    {
        $code = rand(100000, 999999);

        $phone = $request->input('phone_number') ?: User::where('account', $request->input('account'))->first()?->mobile;

        if (!$phone) return fail('账号/手机号不能为空');

        try {
            $result = $easySms->send($request->input('phone_number'), new VerificationCode($code));

            VerificationCodeModel::create([
                'phone_number' => $request->input('phone_number'),
                'code' => $code,
                'getaway' => last($result)['gateway'],
                'expiration_date' => now()->addMinutes(config('sms.expiration'))
            ]);
        } catch (NoGatewayAvailableException  $e) {
            Log::error('SMS_ERROR', $e->results);
            return fail('短信发送失败：' . $e->results['qcloud']['exception']->getMessage());
        }

        return success();
    }
}
