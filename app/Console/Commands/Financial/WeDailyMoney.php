<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Illuminate\Console\Command;

class WeDailyMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:daily-money';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日收益';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = WeBankStock::where('type', '微众活期+Plus')->get();

        foreach ($stocks as $stock) {

            $today = WeBankStockRate::where('prod_code', $stock->code)
                ->orderBy('earnings_rate_date', 'desc')
                ->first();

            $yesterday = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', now()->addDays(-1)->toDateString())
                ->orderBy('earnings_rate_date', 'desc')
                ->first();

            if ($today && $yesterday) { // 今日收益
                $stock->daily_increase_change = $today->unit_net_value - $yesterday->unit_net_value;
                $stock->daily_increase_money = ($today->unit_net_value * 10000 - $yesterday->unit_net_value * 10000);
                $today->daily_increase_money = $stock->daily_increase_money;
            }

            $pre_month_last_day = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', date('Y-m-01'))
                ->orderBy('earnings_rate_date', 'desc')
                ->first();

            if ($today && $pre_month_last_day) $stock->month_increase_money = 10000 * ($today->unit_net_value - $pre_month_last_day->unit_net_value);

            $_left = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', '2025-04-01')
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            $_right = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', '2025-05-01')
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            if ($_left && $_right) $stock->pre_month_increase_money = 10000 * ($_right->unit_net_value - $_left->unit_net_value);

            $stock->save();
        }
    }
}
