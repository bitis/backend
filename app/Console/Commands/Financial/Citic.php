<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class Citic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financial:citic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新每日净值';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = WeBankStock::where('type', 'CiticBank')->limit(1)->get();

        foreach ($stocks as $stock) {
            $this->sync($stock->code, $stock->body);
        }
    }

    public function sync($code, $param): void
    {
        $client = new Client();
        $response = $client->get('https://wap.bank.ecitic.com/NMBFOServer/api.do', [
            'query' => [
                'act' => 'PENFHNLC',
                'isWeb' => 1
            ],
            'json' => json_decode($param),
            'headers' => [
                'cookie' => 'JSESSIONID=03WkB3A7kNwBQnmzchADVOzkeKxDz5; Path=/NMBFOServer; HttpOnly'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $result = json_decode($data['dataPackage']['business'], true);

        if ($result['RETCODE'] != 'AAAAAAA') echo $result['RETMSG'];

        $rates = Arr::sort($result['resultList'], function ($val) {
            return $val['ISSDATE'];
        });

        foreach ($rates as $rate) {
            $date = $this->formatDate($rate['ISSDATE']);

            if (WeBankStockRate::where([
                'prod_code' => $code,
                'earnings_rate_date' => $date
            ])->exists()) continue;

            $today = WeBankStockRate::firstOrCreate([
                'prod_code' => $code,
                'earnings_rate_date' => $date
            ], [
                'accu_net_value' => $rate['TOTNAV'],
                'unit_net_value' => $rate['NAV'],
            ]);

            $yesterday_value = WeBankStockRate::where('prod_code', $code)
                ->where('earnings_rate_date', '<', $date)
                ->orderBy('earnings_rate_date', 'desc')
                ->first()?->unit_net_value;

            if ($today && $yesterday_value) {
                $stock = WeBankStock::where('code', $code)->first();
                $stock->daily_increase_change = $today->unit_net_value - $yesterday_value;
                $stock->daily_increase_money = ($today->unit_net_value * 10000 - $yesterday_value * 10000);
                $today->daily_increase_money = $stock->daily_increase_money;

                if (Carbon::parse($today->earnings_rate_date)->month < date('n')) {
                    $stock->pre_month_increase_money = $stock->month_increase_money + $stock->daily_increase_money;
                    $stock->month_increase_money = 0;
                } else {
                    $stock->month_increase_money += $stock->daily_increase_money;
                }

                $stock->save();
                $today->save();
            }

            WeBankStock::where('code', $code)->update([
                'unit_net_value' => $rate['NAV'],
//                'fund_begin_yield' => $rate['fund_begin_yield'],
//                'month_yield' => $rate['month_yield'],
//                'season_yield' => $rate['season_yield'],
                'value_date' => $date,
            ]);
        }
    }

    /**
     * @param string $start_buy_time 20240906150000
     * @return string
     */
    private function formatDate(string $start_buy_time): string
    {
        return substr($start_buy_time, 0, 4) . '-'
            . substr($start_buy_time, 4, 2) . '-'
            . substr($start_buy_time, 6, 2);
    }
}
