<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\MiniUser;
use EasyWeChat\Work\MiniProgram\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $app = new Application(config('wechat.finance'));

        $session = $app->getUtils()->codeToSession($request->input('code'));

        if (empty($session)) {
            return fail('获取 session 失败');
        }

        $user = MiniUser::firstOrCreate([
            'openid' => $session['openid'],
        ], [
            'token' => Str::random('16'),
        ]);

        return success($user);
    }
}
