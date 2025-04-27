<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CiticBank extends Command
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
    protected $description = '更新中信信芯家族净值';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cookies = $this->refreshCookie();
        $stocks = WeBankStock::where('type', '中信信芯家族')->get();

        foreach ($stocks as $stock) {
            $this->sync($stock, $cookies);
        }
    }

    public function sync($stock, $cookies): void
    {
        $client = new Client();
        $response = $client->post('https://wap.bank.ecitic.com/NMBFOServer/api.do', [
            'query' => [
                'act' => 'PENFHNLC',
                'isWeb' => 1
            ],
            'json' => json_decode($stock->body, true),
            'headers' => [
                'cookie' => $cookies
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
                'prod_code' => $stock->code,
                'earnings_rate_date' => $date
            ])->exists()) continue;

            $today = WeBankStockRate::firstOrCreate([
                'prod_code' => $stock->code,
                'earnings_rate_date' => $date
            ], [
                'accu_net_value' => $rate['TOTNAV'],
                'unit_net_value' => $rate['NAV'],
            ]);

            $yesterday_value = WeBankStockRate::where('prod_code', $stock->code)
                ->where('earnings_rate_date', '<', $date)
                ->orderBy('earnings_rate_date', 'desc')
                ->first()?->unit_net_value;

            if ($today && $yesterday_value) {
                $stock = WeBankStock::where('code', $stock->code)->first();
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

                $yearRate = $this->yearRates($stock->year_rate_body, $cookies);

                WeBankStock::where('code', $stock->code)->update([
                    'unit_net_value' => $rate['NAV'],
                    'rate_value' => $rate['NAV'],
                    'fund_begin_yield' => $yearRate[1]['YEARRATE'],
//                'month_yield' => $rate['month_yield'],
                    'season_yield' => $yearRate[0]['YEARRATE'],
                    'value_date' => $date,
                ]);
            }
        }
    }

    public function yearRates($param, $cookies)
    {
        $client = new Client();
        $response = $client->post('https://wap.bank.ecitic.com/NMBFOServer/api.do', [
            'query' => [
                'act' => 'PE05LSYJ',
                'isWeb' => 1
            ],
            'json' => json_decode($param),
            'headers' => [
                'cookie' => $cookies
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $result = json_decode($data['dataPackage']['business'], true);

        if ($result['RETCODE'] != 'AAAAAAA') echo $result['RETMSG'];

        return $result['resultList'];
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

    private function refreshCookie(): string
    {
        $response = (new Client())->get('https://wap.bank.ecitic.com/NMBFOServer/api.do?act=PEMBPKQY&isWeb=1', [
            'json' => [
                "ISWEBENCRYPY" => 1
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:137.0) Gecko/20100101 Firefox/137.0',
            ]
        ]);

        return collect($response->getHeader('Set-Cookie'))->map(function ($cookie) {
            return explode(';', $cookie)[0];
        })->implode(';');
    }
}
