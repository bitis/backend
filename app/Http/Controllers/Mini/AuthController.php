<?php

namespace App\Http\Controllers\Mini;

use App\Models\Member;
use App\Models\MiniUser;
use EasyWeChat\MiniApp\Application;
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

        $user = Member::firstOrCreate([
            'openid' => $session['openid'],
        ], [
            'token' => Str::random('16'),
        ]);

        return success($user);
    }
}
