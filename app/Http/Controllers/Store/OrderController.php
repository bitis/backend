<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 订单列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with(['products', 'member:id,avatar,name,mobile', 'payment'])
            ->where('store_id', $this->store_id)
            ->when($request->input('member_id'), function ($query, $member_id) {
                $query->where('member_id', $member_id);
            })
            ->when($request->input('keywords'), function ($query, $keywords) {
                $query->where('order_number', 'like', "%{$keywords}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(getPerPage());

        return success($orders);
    }

    /**
     * 订单详情
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $order = Order::where('store_id', $this->store_id)
            ->with('products', 'staffs', 'member:id,avatar,name,mobile')
            ->find($request->input('id'));

        return success($order);
    }
}
