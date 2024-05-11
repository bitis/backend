<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(User::with('job')->where('store_id', $this->store_id)->paginate(getPerPage()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form(Request $request): JsonResponse
    {
        $user = $request->input('id') ? User::where('store_id', $this->store_id)
            ->findOr($request->input('id'), fn() => new User(['store_id' => $this->store_id]))
            : new User(['store_id' => $this->store_id]);

        $exits = User::where('mobile', $request->input('mobile'))->first();

        if ($exits && $exits->id != $user->id) return fail('手机号已存在');

        $user->fill($request->only(['name', 'avatar', 'mobile', 'password', 'status', 'job_id', 'remark']));
        $user->password = bcrypt($request->input('password', config('default.password')));
        $user->save();

        return success();
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        return success(User::with('job', 'permissions')->find($request->input('id')));
    }


    /**
     * 设置权限
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function permission(Request $request): JsonResponse
    {
        $user = User::find($request->input('user_id'));

        if ($user?->store_id != $this->store_id) return fail('用户不存在');

        UserPermission::updateOrCreate([
            'user_id' => $request->input('user_id')
        ], [
            'permissions' => $request->input('permissions')
        ]);

        return success();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        User::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }

}
