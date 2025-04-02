<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\WeBankStockRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeBankController extends Controller
{
    /**
     * 产品列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        return success();
    }

    /**
     * 产品万份收益月度视图
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calendar(Request $request): JsonResponse
    {
        $code = $request->input('code');
        $start_date = $request->input('start_date') ?: date('Y-m-01');
        $end_date = $request->input('end_date') ?: date('Y-m-d');

        $rates = WeBankStockRate::where('prod_code', $code)
            ->whereBetween('earnings_rate_date', [$start_date, $end_date])
            ->orderBy('earnings_rate_date')
            ->get();

        return success($rates);
    }
}
