<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * 用户列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        return success();
    }

    /**
     * 新增、编辑
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $user = User::findOr($request->input('id'), fn() => new User());

        $role = $request->input('role');
        $password = $request->input('password');

        if (!empty($password)) {
            $user->password = bcrypt($password);
            $user->api_token = Str::random(32);
        }

        if (empty($user->id)) {
            $user->password = bcrypt(config('default.password'));
        }

        $user->fill($request->only(['name', 'company_id', 'account', 'mobile', 'status', 'identity_id', 'employee_id', 'remark']));

        $user->save();

        if (!empty($role)) {
            $user->syncRoles($user->company_id . '_' . Str::after($role, '_'));
        }

        return success($user);
    }
}
