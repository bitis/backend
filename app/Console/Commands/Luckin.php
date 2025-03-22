<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Luckin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:luckin';

    protected Client $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '36+1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->client = new Client();

        $timer = strtotime('2025-03-14 12:00:00');

        $validCode = '';

        while (true) {
            $time = $this->time();

            $second = substr($time, 0, 10);
            $millisecond = substr($time, 10, 3);

            if ($second < $timer) {
                $wait = $timer - $second;
                if ($wait > 1) {
                    $this->info('等待 ' . $wait . ' 秒后继续');
                    sleep(floor($wait / 2));
                    if ($wait < 5 && empty($validCode)) {
                        $validCode = $this->getValidCode(); //validCode
                    }
                } else {
                    $waitMillisecond = 1000 - $millisecond;
                    if ($waitMillisecond > 20) {
                        $this->info('等待 ' . $waitMillisecond . ' 毫秒后继续');
                        usleep($waitMillisecond * 1000 - 20000);
                    }
                    $this->kill($validCode ?: $this->getValidCode());
                }
            } else {
                $this->kill($this->getValidCode());
                usleep(900000);
            }
        }
    }


    public function kill($validCode)
    {
        if (empty($validCode)) {
            $this->info('验证码繁忙中...');
            $this->time();
            return;
        }
        $response = $this->client->post('https://mall-api2-demo.jw2008.cn/mall-basic-portal/v2/oms/order/equityPoint/submitOrder', [
            'headers' => $this->headers(),
            'json' => [
                "activityId" => "QYD1885513038220345344",
                "skuCode" => "SKU0027051",
                "validCode" => $validCode,
                "eCode" => ""
            ]
        ]);

        $this->info('秒杀结果: ' . $response->getBody()->getContents());
        $this->time();
    }

    public function getValidCode()
    {
        $response = $this->client->get('https://mall-api2-demo.jw2008.cn/mall-basic-portal/v2/oms/order/validCode', [
            'headers' => $this->headers(),
        ])->getBody()->getContents();

        $result = json_decode($response, true);

        $this->info("获取 validCode: \t" . $result['message'] . "\t" . $result['value']);

        return $result['value'];
    }

    public function headers(): array
    {
        return [
            'Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJhdWQiOiJtYWxsLWNsaWVudCIsInVtc1VzZXJJZCI6MzM4ODk5MywiQWRtaW5Ub2tlbiI6Imh0dHA6Ly9tYWxsLWF1dGgvaW5uZXIvdG9rZW4vZ2V0QWRtaW5Ub2tlbj90b2tlbj0iLCJyZW1vdGVJcCI6IjExNS42MC4xOTMuMTQzIiwibG9hZEJhbGFuY2VyIjoibWFsbC1hdXRoIiwibW9iaWxlIjoiMTUxMzg2NzQ1MDIiLCJhcHBrZXkiOiJOSUJqRmV3WGRkMlNCM0RNeHY1WVJQN0ZVd1B3bHAiLCJleHAiOjE3NDE5MjQ5NjMsIkNsaWVudFRva2VuIjoiaHR0cDovL21hbGwtYXV0aC9pbm5lci90b2tlbi9nZXRDbGllbnRUb2tlbj90b2tlbj0iLCJjaGFubmVsSWQiOjQzfQ.ri9sAkxJlsmOK6mynFzW2a8EZF9pvMLPTVFj0wa-jdBT7JQ7nj1ph4OShY_AeseAz76ymjRP5MZcV_v1NK-TOw',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1 Edg/134.0.0.0',
            'appkey' => 'NIBjFewXdd2SB3DMxv5YRP7FUwPwlp',
            'Content-Type' => 'application/json'
        ];
    }

    private function time()
    {
        $response = $this->client->get('https://ldp.creditcard.ecitic.com/citiccard/lottery-gateway-pay/get-server-time.do');

        $t = json_decode($response->getBody()->getContents(), true)['resultData']['timeMillis'];

        $this->info(date('Y-m-d H:i:s', $t / 1000) . ' ' . $t % 1000);

        return $t;
    }
}
