<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * 会员列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $members = Member::with('level')
            ->when($request->filled('mobile'), function ($query) use ($request) {
                $query->where('mobile', $request->input('mobile'));
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('name')}%");
            })->paginate(getPerPage());

        return success($members);
    }

    /**
     * 创建、修改
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $member = Member::where('store_id', $this->store_id)
            ->findOr(request('id'), fn() => new Member(['store_id' => $this->store_id]));

        $member->fill($request->all());

        $member->save();

        return success();
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        Member::where('store_id', $this->store_id)->where('id', $request->input('id'))->delete();

        return success();
    }


    /**
     * 余额流水
     *
     * @return JsonResponse
     */
    public function transaction(): JsonResponse
    {


        return success();
    }
}
