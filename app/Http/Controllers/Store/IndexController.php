<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockWarningConfig;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * 首页数据
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stat = [
            'new_member' => Member::where('store_id', $this->store_id)->where('created_at', '>', today())->count(),
            'new_order' => Order::where('store_id', $this->store_id)->where('created_at', '>', today())->count(),
        ];

        $warning_num = StockWarningConfig::where('store_id', $this->store_id)->value('min_number') ?? 0;
        $stock_warning = Product::where('store_id', $this->store_id)->where('stock', '<=', $warning_num)->count();

        return success([
            'stat' => $stat,
            'stock_warning' => $stock_warning,
        ]);
    }
}
