<?php

namespace App\Console\Commands\Webank;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Illuminate\Console\Command;

class Increase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:increase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = WeBankStock::all();

        foreach ($stocks as $stock) {
            $today_value = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', now()->addDays(-1)->format('Y-m-d'))
                ->value('unit_net_value');
            $yesterday_value = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', now()->addDays(-1)->format('Y-m-d'))
                ->orderBy('earnings_rate_date', 'desc')
                ->first()?->unit_net_value;

            if ($today_value && $yesterday_value) {
                $stock->daily_increase_money = ($today_value  * 10000 - $yesterday_value * 10000);
                $stock->save();
            }
        }
    }

    public function numberOrNull($value)
    {
        return is_numeric($value) ? $value : null;
    }


    /**
     * @param string $start_buy_time 20240906150000
     * @return string
     */
    private function formatDate(string $start_buy_time): string
    {
        return substr($start_buy_time, 0, 4) . '-'
            . substr($start_buy_time, 4, 2) . '-'
            . substr($start_buy_time, 6, 2) . ' '
            . substr($start_buy_time, 8, 2) . ':'
            . substr($start_buy_time, 10, 2) . ':'
            . substr($start_buy_time, 12, 2);
    }
}
