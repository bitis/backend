<?php

namespace App\Console\Commands;

use App\Common\DingTalk;
use App\Common\Messages\VerificationCode;
use EasyWeChat\OfficialAccount\Application;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Overtrue\EasySms\EasySms;

class Lenovo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lenovo';

    protected int $send_time = 0;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Application $application)
    {
        $client = new Client();

        while (true) {
            try {
                $response = $client->request('get', 'https://f.lenovo.com.cn/goods/new/detail/B00001?terminal=2&gcode=1037657&roomId=', [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
                        'Accept' => 'application/json, text/plain, */*',
                        'Accept-Language' => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Content-Type' => 'application/json;charset=utf-8',
                        'Referer' => 'https://mitem.lenovo.com.cn/'
                    ]
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if ($data['result']['stockWithCdt']['salesNumber'] > 0) {
                    $this->info(now()->toDateTimeString() . "\t" . $data['result']['stockWithCdt']['salesNumber']);
                    if ($this->send_time + 60 < time()) {
                        DingTalk::send("500 立减金，库存：" . $data['result']['stockWithCdt']['salesNumber']);
                        $this->send_time = time();
                    }
                }
            } catch (\Exception $exception) {
                $this->error(now()->toDateTimeString() . "\t" . $exception->getMessage());
            }

            sleep(20);
        }
    }
}
