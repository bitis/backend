<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreController extends Controller
{

    /**
     * 门店资料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $store = $request->user()->store;

        return success($store);
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

}
