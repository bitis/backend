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

        $phone = $request->input('mobile') ?: User::where('mobile', $request->input('mobile'))->first()?->mobile;

        if (!$phone) return fail('账号/手机号不能为空');

        try {
            $result = $easySms->send($request->input('mobile'), new VerificationCode($code));

            VerificationCodeModel::create([
                'mobile' => $request->input('mobile'),
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
