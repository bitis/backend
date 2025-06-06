<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\Register;
use App\Models\Enumerations\UserStatus;
use App\Models\Store;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * 注册
     *
     * @param Login $request
     * @return JsonResponse
     */
    public function register(Register $request): JsonResponse
    {
        if (!VerificationCode::verify($request->input('mobile'), $request->input('verify_code')))
            return fail('验证码错误');

        $store = Store::create([
            'name' => $request->input('store_name'),
            'industry_id' => $request->input('industry_id'),
            'contact_name' => $request->input('store_name'),
            'contact_mobile' => $request->input('mobile'),
            'expiration_date' => now()->addDays(config('default.expiration_date')),
        ]);

        $user = User::create(array_merge($request->only([
            'mobile'
        ]), [
            'name' => '管理员',
            'password' => bcrypt($request->input('password', config('default.password'))),
            'store_id' => $store->id,
            'is_admin' => true,
            'status' => UserStatus::Normal->value
        ]));

        $user->save();

        $token = $user->createToken($request->header('User-Agent', 'Unknown'));

        $user->token = $token->plainTextToken;

        return success($user);
    }

    /**
     * 登录
     *
     * @param Login $request
     * @return JsonResponse
     */
    public function login(Login $request): JsonResponse
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');

        $user = User::with('store')->where('mobile', $mobile)->first();

        if (!Hash::check($password, $user->password)) {
            return fail('密码校验失败');
        }

        if ($user->status == UserStatus::Disable->value) {
            return fail('账号已被禁用');
        }

        if ($user->status == UserStatus::Destroy->value) {
            return fail('账号不存在');
        }

        $store = $user->store;

        if ($store->expiration_date < now()) {
            return fail('账号已过期');
        }

        if ($store->blocked) {
            return fail('账号被禁用：' . $store->block_reason);
        }

        $token = $user->createToken($request->header('User-Agent', 'Unknown'));

        $user->token = $token->plainTextToken;

        return success($user);
    }


    /**
     * 退出登录
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return success();
    }


    /**
     * 个人资料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
        $user = $request->user()->load('store');

        return success($user);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        return success();
    }


    /**
     * 编辑资料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $user = $request->user();

        $password = $request->input('password');
        $editPassword = $request->input('editPassword');

        if (!empty($password) && !empty($editPassword)) {
            if (!Hash::check($password, $user->password)) {
                return fail('密码校验失败');
            }

            $user->password = bcrypt($editPassword);

            $user->token = Str::random(32);
        }

        $user->fill($request->only(['name', 'mobile', 'push_id']));

        $user->save();

        return success();
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $mobile = $request->input('mobile');

        $user = User::where('mobile', $mobile)->first();

        if (!VerificationCode::verify($mobile, $request->input('verify_code')))
            return fail('验证码错误');

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return success();
    }

    /**
     * 修改手机号
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function mobile(Request $request): JsonResponse
    {
        $user = $request->user();
        $mobile = $request->input('mobile');

        if (!VerificationCode::verify($mobile, $request->input('verify_code')))
            return fail('验证码错误');

        $user->mobile = $mobile;
        $user->save();

        return success();
    }


    /**
     * 修改密码
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function password(Request $request): JsonResponse
    {
        $user = $request->user();
        $password = $request->input('password');

        if (!Hash::check($password, $user->password)) {
            return fail('密码校验失败');
        }

        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return success();
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        $password = $request->input('password');

        if (!Hash::check($password, $user->password)) {
            return fail('密码校验失败');
        }

        $user->status = UserStatus::Destroy->value;

        $user->save();

        $user->currentAccessToken()->delete();

        return success();
    }

}
