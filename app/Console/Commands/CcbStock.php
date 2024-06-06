<?php

namespace App\Console\Commands;

use App\Common\Messages\VerificationCode;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Overtrue\EasySms\EasySms;

class CcbStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ccb-stock';

    protected $send_time = 0;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(EasySms $easySms)
    {
        while (true) {
            $this->info(now()->toDateTimeString());
            foreach (['TCC2024040800000019', 'TCC2024012400005471'] as $channelCategoryId) {
                $list = $this->get($channelCategoryId);
                if ($list) {
                    foreach ($list['data'] as $item) {
                        if ($item['remainingInventory'] > 0) {
                            $this->info($item['activityName'] . ':' . $item['remainingInventory']);
                            if ($this->send_time + 60 < time()) {
                                $easySms->send('18336221323', new VerificationCode('0000'));
                                $easySms->send('15138674502', new VerificationCode('0000'));
                            }
                        }
                    }
                }
            }
            sleep(10);
        }
    }

    public function get($channelCategoryId)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://cy.cloud.ccb.com/gateway/goods-server/goods/ccBeanCategory/queryProduct', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G973U) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/14.2 Chrome/87.0.4280.141 Mobile Safari/537.36',
                'Accept' => 'application/json, text/plain, */*',
                'Accept-Language' => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => '5d5b8368a0d045509592bbb14fbb5e30',
                'ChannelId' => 'TCH2022022500000207',
                'Channel' => 'JH-0007',
                'traceId' => 'acea5b62171291020265097485d3ad3',
                'Origin' => 'https://cy.cloud.ccb.com',
                'Connection' => 'keep-alive',
                'Referer' => 'https://cy.cloud.ccb.com/qymall/ccbean/exchange?isBack='
            ],
            'json' => [
                "channel" => "JH-0007",
                "channelCategoryId" => $channelCategoryId,
                "city" => "410100",
                "province" => "410000",
                "channelId" => "TCH2022022500000207",
                "type" => ""
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
