<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('store_id', $this->store_id)
            ->when($request->input('keywords'), function ($query, $keywords) {
                $query->where('order_no', 'like', "%{$keywords}%")
                    ->orWhere('name', 'like', "%{$keywords}%")
                    ->orWhere('mobile', 'like', "%{$keywords}%");
            })
            ->paginate(getPerPage());

        return success($orders);
    }
}
