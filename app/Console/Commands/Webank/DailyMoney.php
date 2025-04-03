<?php

namespace App\Console\Commands\Webank;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Illuminate\Console\Command;

class DailyMoney extends Command
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
        $stocks = WeBankStock::all();

        foreach ($stocks as $stock) {
            $rates = WeBankStockRate::where('prod_code', $stock->code)->orderBy('earnings_rate_date')->get();

            $yesterday = 1;

            foreach ($rates as $rate) {
                $rate->daily_increase_money = $rate->unit_net_value * 10000 - $yesterday * 10000;
                $rate->save();
                $yesterday = $rate->unit_net_value;
            }
        }
    }
}
