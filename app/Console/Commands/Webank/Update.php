<?php

namespace App\Console\Commands\Webank;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:update';

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
        $start_date = today()->addDays(-7)->format('Ymd');
        $end_date = today()->format('Ymd');

        $stocks = WeBankStock::all();

        foreach ($stocks as $stock) {
            $param = [
                'prod_code' => $stock->code, // EW2629D
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];
            $this->sync($param);
        }
    }

    public function sync($param): void
    {
        $query = [
            'user_type' => 0,
            'terminal_type' => 'Android',
            'a' => 'security info',
            'os_ver' => 11,
            'device_id' => '8b904e8aeb375e9a1c22b97d70510215',
            'uid_type' => 'qq',
            'net_type' => '',
            'session' => 'DCEE5ACF79F91753FFAF6812C54E3513',
            'target_type' => 0,
            'mid' => '8b904e8aeb375e9a1c22b97d70510215',
            'machine_info' => 'GM1910',
            'app_ver_code' => 901039,
            'uid' => '0119D4AF73A4605FF49773551B68CA0E',
            'app_ver' => '9.1.3',
            'appid' => 1104566348,
            'paid' => 'f442d9ea8c58a64f8ac0273355eeae64-1a4cace56fd6101cbcb56606f1eb2760-c7fc3150c6864d86ac57947ff82f4066',
            'imei' => '',
            'session_type' => 'token',
            'aid' => '8b904e8aeb375e9a1c22b97d70510215',
            'wx_union_id' => '',
            'channel_id' => 'oppo_64',
            'h5_ver' => 6020,
            'oaid' => '',
            'param' => json_encode($param),
        ];
        $client = new Client();
        $response = $client->get('https://personalv6.webankwealth.com/wm-hjhtr/wm-pqs/query/ta/stock_rates', ['query' => $query]);
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data['ret_data'] as $rate) {

            if (WeBankStockRate::where([
                'prod_code' => $rate['prod_code'],
                'earnings_rate_date' => $rate['earnings_rate_date']
            ])->exists()) return;

            $today = WeBankStockRate::firstOrCreate([
                'prod_code' => $rate['prod_code'],
                'earnings_rate_date' => $rate['earnings_rate_date']
            ], $rate);

            $yesterday_value = WeBankStockRate::where('prod_code', $rate['prod_code'])
                ->where('earnings_rate_date', '<', $rate['earnings_rate_date'])
                ->orderBy('earnings_rate_date', 'desc')
                ->first()?->unit_net_value;

            if ($today && $yesterday_value) {
                $stock = WeBankStock::where('code', $rate['prod_code'])->first();
                $stock->daily_increase_money = ($today->unit_net_value  * 10000 - $yesterday_value * 10000);
                $stock->month_increase_money += $stock->month_increase_money;
                $today->daily_increase_money = $stock->daily_increase_money;
                $today->save();
            }

            WeBankStock::where('code', $rate['prod_code'])->update([
                'unit_net_value' => $rate['unit_net_value'],
                'fund_begin_yield' => $rate['fund_begin_yield'],
                'month_yield' => $rate['month_yield'],
                'season_yield' => $rate['season_yield'],
                'value_date' => $rate['earnings_rate_date'],
            ]);
        }
    }
}
