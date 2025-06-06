<?php

namespace App\Http\Controllers\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
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
        $type = $request->input('type', '微众活期+Plus');
        $column = $request->input('column');
        $direction = $request->input('direction');

        $stocks = WeBankStock::when($type, function ($query, $type) use ($direction) {
            $query->where('type', $type);
        })->orderBy($column ?: 'daily_increase_money', $direction ?: 'desc')->get();

        foreach ($stocks as $stock) {
            $stock->rate_value = number_format($stock->rate_value ?: 0, 2);
            $stock->unit_net_value = number_format($stock->unit_net_value ?: 0, 6);
            $stock->adjust_unit_net_value = number_format($stock->adjust_unit_net_value ?: 0, 6);
            $stock->fund_begin_yield = number_format($stock->fund_begin_yield ?: 0, 2);
            $stock->month_yield = number_format($stock->month_yield ?: 0, 2);
            $stock->season_yield = number_format($stock->season_yield ?: 0, 2);
            $stock->month = number_format($stock->month ?: 0, 2);
            $stock->three_month = number_format($stock->three_month ?: 0, 2);
            $stock->half_year_yield = number_format($stock->half_year_yield ?: 0, 2);
            $stock->six_month = number_format($stock->six_month ?: 0, 2);
            $stock->twelve_month_yield = number_format($stock->twelve_month_yield ?: 0, 2);
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

        if (strtotime($start_date) >= strtotime($end_date)) {
            return fail("卖出日期应晚于买入日期");
        }

        $stock = WeBankStock::where('code', $code)->first();

        $rates = WeBankStockRate::where('prod_code', $code)
            ->whereBetween('earnings_rate_date', [$start_date, $end_date])
            ->orderBy('earnings_rate_date')
            ->get();

        $stock->halfyearyield = $stock->half_year_yield;
        $stock->amount = $amount;
        $stock->confirm_date = ''; // 确认日期
        $stock->confirm_value = 0; // 确认净值
        $stock->sell_value = 0; // 卖出净值
        $stock->sell_date = ''; // 卖出日期
        $stock->confirm_number = 0; // 确认份额
        $stock->total_bonus = 0; // 总收益
        $stock->day_of_zero_bonus = 0; // 0收天数
        $stock->average_bonus = 0; // 日均收益

        $stock->earliest_date = today()->addDays(-366)->max($stock->start_buy_time)->format('Y-m-d');

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

            $bonus = $after_amount - $before_amount;
            $stock->total_bonus += $bonus;
            $stock->day_of_zero_bonus += $bonus == 0 ? 1 : 0;

            $results[] = [
                'date' => $rate->earnings_rate_date,
                'unit_value' => number_format($rate->unit_net_value, '6'),
                'number' => number_format($stock->confirm_number, 2),
                'amount' => number_format($after_amount, 2),
                'change' => number_format($bonus, 2)
            ];
            $before_amount = $after_amount;
            $stock->sell_value = $rate->unit_net_value;
            $stock->sell_date = $rate->earnings_rate_date;
        }

        $stock->results = array_reverse($results);
        $stock->total_days = Carbon::parse($stock->sell_date)->diffInDays(Carbon::parse($stock->confirm_date));
        $stock->average_bonus = $stock->total_bonus / $stock->total_days;
        $stock->period_yield = ($stock->sell_value - $stock->confirm_value) / $stock->confirm_value / $stock->total_days * 365;

        return success($stock);
    }
}
