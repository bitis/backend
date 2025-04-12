<?php

namespace App\Http\Controllers\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeBankController extends Controller
{
    private function rate($money, $unit_val, $date): int|string
    {
        if ($money == 0) return 0;
        return number_format(($money * $date) / ($unit_val * 10000), 4);
    }

    /**
     * 产品列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $column = $request->input('column');
        $direction = $request->input('direction');

        $stocks = WeBankStock::when($column, function ($query, $column) use ($direction) {
            $query->orderBy($column, $direction);
        })->get();

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
            $stock->daily_increase_rate = $this->rate($stock->daily_increase_money, $stock->unit_net_value, 365);
            $stock->month_increase_rate = $this->rate($stock->month_increase_rate, $stock->unit_net_value, 12);
            $stock->daily_increase_money = number_format($stock->daily_increase_money ?: 0, 2);
            $stock->month_increase_money = number_format($stock->month_increase_money ?: 0, 2);
            $stock->pre_month_increase_money = number_format($stock->pre_month_increase_money ?: 0, 2);
        }

        return success($stocks);
    }

    /**
     * 产品万份收益测算
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        $code = $request->input('code');
        $start_date = $request->input('start_date') ?: now()->addDays(-31)->toDateString();
        $end_date = $request->input('end_date') ?: date('Y-m-d');
        $amount = $request->input('amount') ?: 10000;

        $stock = WeBankStock::where('code', $code)->first();

        $rates = WeBankStockRate::where('prod_code', $code)
            ->whereBetween('earnings_rate_date', [$start_date, $end_date])
            ->orderBy('earnings_rate_date')
            ->get();

        $stock->amount = $amount;
        $stock->confirm_date = ''; // 确认日期
        $stock->confirm_value = 0; // 确认净值
        $stock->confirm_number = 0; // 确认份额

        $results = [];
        $before_amount = $amount;

        foreach ($rates as $index => $rate) {
            if ($index == 0) {
                $stock->confirm_date = $rate->earnings_rate_date;
                $stock->confirm_value = $rate->unit_net_value;
                $stock->confirm_number = round($amount / $stock->confirm_value, 2);
                continue;
            }
            $after_amount = $stock->confirm_number * $rate->unit_net_value;
            $results[] = [
                'date' => $rate->earnings_rate_date,
                'unit_value' => number_format($rate->unit_net_value, '6'),
                'number' => number_format($stock->confirm_number, 2),
                'amount' => number_format($after_amount, 2),
                'change' => number_format($after_amount - $before_amount, 2)
            ];
            $before_amount = $after_amount;
        }

        $stock->results = array_reverse($results);

        return success($stock);
    }
}
