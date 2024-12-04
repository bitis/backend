<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;

class Hebao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hebao';

    protected Client $client;

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
        $this->client = new Client();

        $timer = strtotime('2024-12-04 15:00:00');

        $this->getSessionStatus();

        while (true) {
            $time = $this->time();

            $second = substr($time, 0, 10);
            $millisecond = substr($time, 10, 3);

            if ($second < $timer) {
                $wait = $timer - $second;
                if ($wait > 1) {
                    $this->info('等待 ' . $wait . ' 秒后继续');
                    sleep(floor($wait / 2));
                } else {
                    $waitmillsecond = 1000 - $millisecond;
                    if ($waitmillsecond > 20) {
                        $this->info('等待 ' . $waitmillsecond . ' 毫秒后继续');
                        usleep($waitmillsecond * 1000 - 20000);
                    }
                    $this->kill();
                }
            } else {
                $this->kill();
            }
        }
    }

    public function getSessionStatus(): void
    {
        $response = $this->client->post('https://ump.cmpay.com/activities/v1/members/membersIndex', [
            'headers' => $this->headers(),
            'cookies' => $this->cookie(),
            'json' => [
                "channelSource" => "1",
                "channelNoticeType" => ""
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if ($result['msgCd'] != 'MKM00000') {
            $this->error($result['msgInfo']);
            die();
        }

        $this->info("Session Status: OK\t startDateTime: " . $result['startDateTime']);
    }

    public function kill()
    {
//        $this->info('秒杀中...' . $this->time());
        $response = $this->client->post('https://ump.cmpay.com/activities/v1/members/integralTicketReceive', [
            'headers' => $this->headers(),
            'cookies' => $this->cookie(),
            'json' => [
                "prizeNo" => "JFMS20239105",
                "opDfp" => "ODa6znjVqKGvcjk21TQB2UAU1vOSrzxsaellG8yylnk7Ij6gpODzL-e67Z6AG7XfMeftICGfjS03DxlwDgtrKMryq9JAOKtemouURJ4QdZktBc_C8rtNPUGvFWy1nHZnXZGPBLq-2g4DNaRcx6YVRUF6iweRCRJu",
                "sign" => "B"
            ]
        ]);

        $this->info('秒杀结果: ' . $response->getBody()->getContents());

        die();
    }

    private function cookie(): CookieJar
    {
        $content = 'sid=MCALOGIN-2bd7c7bf-9a55-401e-bea2-f34a2bc091bf; act_sid=act-5dab053d-a4cf-453d-8431-0d9395f3679b';

        $cookies = [];

        foreach (explode('; ', $content) as $cookie) {
            list($key, $val) = explode('=', $cookie);
            $cookies[$key] = $val;
        }

        return CookieJar::fromArray($cookies, 'ump.cmpay.com');
    }

    private function headers(): array
    {
        $content = 'X-Requested-With: XMLHttpRequest
user-agent: Mozilla/5.0 (Linux; Android 11; GM1910 Build/RKQ1.201022.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/87.0.4280.141 Mobile Safari/537.36 uni-app Html5Plus/1.0 (Immersed/36.533333)
Content-Type: application/json
Host: ump.cmpay.com
Connection: keep-alive';
        $headers = [];

        foreach (explode("\n", $content) as $header) {
            list($key, $val) = explode(': ', $header);
            $headers[$key] = $val;
        }
        return $headers;
    }

    private function time()
    {
        $response = $this->client->get('https://ldp.creditcard.ecitic.com/citiccard/lottery-gateway-pay/get-server-time.do');

        $t = json_decode($response->getBody()->getContents(), true)['resultData']['timeMillis'];

        $this->info(date('Y-m-d H:i:s', $t / 1000) . ' ' . $t % 1000);

        return $t;
    }
}
