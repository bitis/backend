<?php

namespace App\Console\Commands\Webank;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Rate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'we-bank:rate';

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
        $start_date = '20250101';
        $end_date = today()->format('Ymd');

        $stocks = WeBankStock::all();

        $end_date = today()->format('Ymd');


        foreach ($stocks as $stock) {
            $param = [
                'prod_code' => $stock->code, // EW2629D
                'start_date' => Carbon::parse($stock->start_buy_time)->format('Ymd'),
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

        dd($data);
        foreach ($data['ret_data'] as $rate) {
            WeBankStockRate::firstOrCreate([
                'prod_code' => $rate['prod_code'],
                'earnings_rate_date' => $rate['earnings_rate_date']
            ], $rate);
        }
    }
}
