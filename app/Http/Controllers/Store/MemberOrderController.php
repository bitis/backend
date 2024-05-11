<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberOrderController extends Controller
{
    /**
     * 会员订单列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('store_id', $this->store_id)
            ->when($request->input('type'), fn($query) => $query->where('type', $request->input('type')))
            ->where('member_id', $request->input('member_id'))
            ->with('products')
            ->orderByDesc('id')
            ->paginate(getPerPage());

        return success($orders);
    }

    /**
     * 会员订单详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $order = Order::where('store_id', $this->store_id)
            ->with('products', 'staffs')
            ->find($request->input('id'));

        return success($order);
    }
}
