<?php

namespace App\Console\Commands\Financial;

use App\Models\WeBankStock;
use App\Models\WeBankStockRate;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

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
        $stocks = WeBankStock::where('type', '中信信芯家族')->where('id', '>=', 44)->get();

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

        $rates = Arr::sortDesc($result['resultList'], function ($val) {
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
            }

            WeBankStock::where('code', $stock->code)->update([
                'unit_net_value' => $rate['NAV'],
                'rate_value' => $rate['NAV'],
                'value_date' => $date,
            ]);
        }

        $this->info($stock->name);

        if ($yearRate = $this->yearRates($stock->year_rate_body, $cookies)) {
            WeBankStock::where('code', $stock->code)->update([
                'fund_begin_yield' => trim($yearRate[0]['YEARRATE'], '%'),
                'season_yield' => trim($yearRate[0]['YEARRATE'], '%'),
            ]);
        }
    }

    public function yearRates($param, $cookies)
    {
        $client = new Client();
        $response = $client->post('https://wap.bank.ecitic.com/NMBFOServer/api.do?act=PE05LSYJ&isWeb=1', [
            'json' => json_decode($param),
            'headers' => [
                'accept' => 'application/json',
                'Referer' => 'https://wap.bank.ecitic.com/NMBFOServer/MobileBankWeb/?index=Share.FinancialShare.ShareNew&key=6F7E667D9FDC73AEF88471DCE983BCB5BD9CF77A17D3C486CE7CFC6BE500672C1DB125E45701E09C4FD93CCE99E1E72F3AB47F7BD947374537C36B1BFF8CC5F3F3951F8943F04829136E2EB5A31FC8873867F4804FACBB2C5678E92E8DC1006B1A9C19A75BF8B3DB1B39A41B06A04E8AB6FBC231425D17FC3B37760AE8A762F2BEC427902793509DC149764BD1E82A569C4BDD6B0FF0D3DE8B036514687DE497798AB58A4529994BB55A5DBFE9D71216144783584CB98B2A870E7FF437BB1E448E6B09D8359992B5A5B5B6EB4F0471196B3DE208B3E908AF132CE3F3F16C970FC48C82E1175D35432FE6C3FA43F7AF5645F6BCEC14EB7264EC4D36DF6C1B5C0B7A2F029C433C2A5F9A2A00A74EEEFA09F889DD432CF2F3F87939E7EB3B5FB3764657F657039706B321202F0EA2850AB8823AB35DBB8E297384547E449EF51BAB3A9C4FECA165399962168FCB39FAABC2E16F5790E0B1A82D8D722D0B45111F38EC4B5A802A30D6E38BD262E49E4648FF39222AEB8D2BCB425D9EEEB9B831A914DBD00F4B36AA7F8DE18A8D147DB2200785F5C9F060F49007A2F0BBE7E90F064BAA39E08FD348C834C05745E3D014A588389BB53B127F4929A98285B3697338C33BB4B7EBDA638A5A297B6C9794E1BE0D45607B1784F31E0CAC6353AE24D1EB1F8E6B09D8359992B587ADF470F5ED5801129DE3F8AEA587BF2AA18BACFCCBB9B51197FA3AE8B3B4D47E879794D64BD902D3EC3575F6376F9E66C0F4E0998AA330E777FFF09ED11B16&source=xfxlcCITICBANKLOGO',
                'user-agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1 Edg/137.0.0.0',
                'cookie' => $cookies
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $result = json_decode($data['dataPackage']['business'], true);

        if ($result['RETCODE'] != 'AAAAAAA') $this->warn($result['RETMSG']);

        return empty($result['resultList']) ? null : $result['resultList'];
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
        $response = (new Client())->get('https://wap.bank.ecitic.com/NMBFOServer/MobileBankWeb/?index=Share.FinancialShare.ShareNew&key=6F7E667D9FDC73AEF88471DCE983BCB5BD9CF77A17D3C486CE7CFC6BE500672C1DB125E45701E09C4FD93CCE99E1E72F3AB47F7BD947374537C36B1BFF8CC5F3F3951F8943F04829136E2EB5A31FC8873867F4804FACBB2C5678E92E8DC1006B1A9C19A75BF8B3DB1B39A41B06A04E8AB6FBC231425D17FC3B37760AE8A762F2BEC427902793509DC149764BD1E82A569C4BDD6B0FF0D3DE8B036514687DE497798AB58A4529994BB55A5DBFE9D71216144783584CB98B2A870E7FF437BB1E448E6B09D8359992B5A5B5B6EB4F0471196B3DE208B3E908AF132CE3F3F16C970FC48C82E1175D35432FE6C3FA43F7AF5645F6BCEC14EB7264EC4D36DF6C1B5C0B7A2F029C433C2A5F9A2A00A74EEEFA09F889DD432CF2F3F87939E7EB3B5FB3764657F657039706B321202F0EA2850AB8823AB35DBB8E297384547E449EF51BAB3A9C4FECA165399962168FCB39FAABC2E16F5790E0B1A82D8D722D0B45111F38EC4B5A802A30D6E38BD262E49E4648FF39222AEB8D2BCB425D9EEEB9B831A914DBD00F4B36AA7F8DE18A8D147DB2200785F5C9F060F49007A2F0BBE7E90F064BAA39E08FD348C834C05745E3D014A588389BB53B127F4929A98285B3697338C33BB4B7EBDA638A5A297B6C9794E1BE0D45607B1784F31E0CAC6353AE24D1EB1F8E6B09D8359992B587ADF470F5ED5801129DE3F8AEA587BF2AA18BACFCCBB9B51197FA3AE8B3B4D47E879794D64BD902D3EC3575F6376F9E66C0F4E0998AA330E777FFF09ED11B16&source=xfxlcCITICBANKLOGO', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:137.0) Gecko/20100101 Firefox/137.0',
            ]
        ]);

        $cookies = collect($response->getHeader('Set-Cookie'))->map(function ($cookie) {
            return explode(';', $cookie)[0];
        });

//si=724643a1-191b-4799-a45e-e772bdaa071c; is_si_expire=0; iss_webanalytics_id=6806eef3-976b-43f6-9f7e-d5435ee3ff3c; nu=1
        $response = (new Client())->get('https://wap.bank.ecitic.com/NMBFOServer/api.do?act=PEMBPKQY&isWeb=1', [
            'json' => [
                "ISWEBENCRYPY" => 1
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:137.0) Gecko/20100101 Firefox/137.0',
                'cookie' => $cookies->implode(';')
            ]
        ]);

        return $cookies->merge(collect($response->getHeader('Set-Cookie'))->map(function ($cookie) {
                return explode(';', $cookie)[0];
            }))->implode(';') . ';' . 'si=b43d2c34-b7ea-4ebc-80e3-0241f58c8f1a;is_si_expire=0;iss_webanalytics_id=b4a27cc1-d077-46d0-bf4a-fe6fe21ac91f;nu=1';
    }
}
