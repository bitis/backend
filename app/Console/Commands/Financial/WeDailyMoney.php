<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
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
        $stocks = WeBankStock::where('type', 'WeBank')->where('id', '>', 20)->get();

        foreach ($stocks as $stock) {
            $pre_month_last_day = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', date('Y-m-01'))
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            $today = WeBankStockRate::where('prod_code', $stock->code)
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            $stock->month_increase_money = 10000 * ($today->unit_net_value - $pre_month_last_day->unit_net_value);

            $_left = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', '2025-03-01')
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            $_right = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', '2025-04-01')
                ->orderBy('earnings_rate_date', 'desc')
                ->first();
            if ($_left && $_right) $stock->pre_month_increase_money = 10000 * ($_right->unit_net_value - $_left->unit_net_value);

            $stock->save();
        }
    }
}
