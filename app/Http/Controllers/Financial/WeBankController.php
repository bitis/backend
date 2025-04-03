<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\WeBankStock;
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
        $stocks = WeBankStock::all();

        foreach ($stocks as $stock) {
            $stock->rate_value = number_format($stock->rate_value ?: 0, 2);
            $stock->unit_net_value = number_format($stock->unit_net_value ?: 0, 6);
            $stock->adjust_unit_net_value = number_format($stock->adjust_unit_net_value ?: 0, 6);
            $stock->fund_begin_yield = number_format($stock->fund_begin_yield ?: 0, 2);
            $stock->month_yield = number_format($stock->month_yield ?: 0, 2);
            $stock->season_yield = number_format($stock->season_yield ?: 0, 2);
            $stock->month = number_format($stock->month ?: 0, 2);
            $stock->threemonth = number_format($stock->threemonth ?: 0, 2);
            $stock->halfyearyield = number_format($stock->halfyearyield ?: 0, 2);
            $stock->sixmonth = number_format($stock->sixmonth ?: 0, 2);
            $stock->twelvemonthyield = number_format($stock->twelvemonthyield ?: 0, 2);
            $stock->start_buy_time = number_format($stock->start_buy_time ?: 0, 2);
            $stock->daily_increase_money = number_format($stock->daily_increase_money ?: 0, 2);
            $stock->month_increase_money = number_format($stock->month_increase_money ?: 0, 2);
            $stock->pre_month_increase_money = number_format($stock->pre_month_increase_money ?: 0, 2);
        }

        return success($stocks);
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
