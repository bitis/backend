<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use Illuminate\Console\Command;

class Stock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:stock';

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
        $json = file_get_contents('stock.json');
        $data = json_decode($json, true);
        $stocks = $data['ret_data']['list'];

        foreach ($stocks as $stock) {
            WeBankStock::updateOrCreate([
                'name' => $stock['product_name'],
                'code' => $stock['product_code'],
            ], [
                'name' => $stock['product_name'],
                'code' => $stock['product_code'],
                'days_of_product_period' => $stock['days_of_product_period'],
                'product_period' => $stock['product_period'],
                'bank_short_name' => $stock['extra_info']['bank_short_name'],
                'bank_name' => $stock['extra_info']['bank_short_name'],
                'rate_value' => $this->numberOrNull($stock['rate_value']),
                'unit_net_value' => $this->numberOrNull($stock['unit_net_value']),
                'adjust_unit_net_value' => $this->numberOrNull($stock['adjust_unit_net_value']),
                'fund_begin_yield' => $this->numberOrNull($stock['ladder_rate']['fundbeginyield']),
                'month_yield' => $this->numberOrNull($stock['ladder_rate']['monthyield']),
                'month' => $this->numberOrNull($stock['ladder_rate']['month']),
                'season_yield' => $this->numberOrNull($stock['ladder_rate']['seasonyield']),
                'three_month' => $this->numberOrNull($stock['ladder_rate']['threemonth']),
                'half_year_yield' => $this->numberOrNull($stock['ladder_rate']['seasonyield']),
                'six_month' => $this->numberOrNull($stock['ladder_rate']['sixmonth']),
                'twelve_month_yield' => $this->numberOrNull($stock['ladder_rate']['seasonyield']),
                'start_buy_time' => $this->formatDate($stock['start_buy_time']),
            ]);
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
