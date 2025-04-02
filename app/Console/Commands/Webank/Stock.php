<?php

namespace App\Console\Commands\Webank;

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
        $stocks = $data['ret_data']['product_list'];

        foreach ($stocks as $stock) {
            WeBankStock::create([
                'name' => $stock[''],
                'code' => $stock[''],
                'days_of_product_period' => $stock[''],
                'product_period' => $stock[''],
                'bank_short_name' => $stock['extra_info']['bank_short_name'],
                'bank_name' => $stock['extra_info']['bank_short_name'],
                'rate_value' => $stock['rate_value'],
                'unit_net_value' => $stock['unit_net_value'],
                'adjust_unit_net_value' => $stock['adjust_unit_net_value'],
                'fundbeginyield' => $stock['ladder_rate']['fundbeginyield'],
                'monthyield' => $stock['ladder_rate']['monthyield'],
                'month' => $stock['ladder_rate']['month'],
                'seasonyield' => $stock['ladder_rate']['seasonyield'],
                'threemonth' => $stock['ladder_rate']['threemonth'],
                'halfyearyield' => $stock['ladder_rate']['seasonyield'],
                'sixmonth' => $stock['ladder_rate']['sixmonth'],
                'twelvemonthyield' => $stock['ladder_rate']['seasonyield'],
            ]);
        }
    }
}
