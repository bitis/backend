<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\OfficialAccountConfig;
use App\Models\Product;
use App\Models\User;
use App\Models\UserPermission;
use EasyWeChat\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->input('status');
        $staffs = User::with('job')
            ->where('store_id', $this->store_id)
            ->when(strlen($status), fn($query) => $query->where('status', $status))
            ->paginate(getPerPage());

        return success($staffs);
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

        $user->fill($request->only(['name', 'avatar', 'mobile', 'password', 'status', 'job_id', 'status', 'remark']));
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
        return success(User::with('job')->find($request->input('id')));
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
     * 设置状态
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setStatus(Request $request): JsonResponse
    {
        User::where('store_id', $this->store_id)
            ->where('id', $request->input('id'))
            ->update(['status' => $request->input('status', 0)]);

        return success();
    }

    /**
     * 微信绑定二维码
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function qrcode(Request $request): JsonResponse
    {
        $config = OfficialAccountConfig::find($this->store()->official_account_id)->toArray();
        $app = Factory::officialAccount($config);
        $result = $app->qrcode->temporary(json_encode([
            'k' => 'staff-bind',
            'v' => $request->input('id')
        ]), 2592000);

        return success([
            'url' => $app->qrcode->url($result["ticket"])
        ]);
    }

}
