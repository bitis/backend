<?php

namespace App\Console\Commands\Monitor;

use App\Models\VisaProduct;
use EasyWeChat\MiniApp\Application;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Lenovo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:lenovo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '联想立减金库存提醒';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();

        $products = VisaProduct::where('type', VisaProduct::TYPE_LENOVO)->get();
        $app = new Application(config('wechat.finance'));

        foreach ($products as $product) {
            $response = $client->request('get', 'https://f.lenovo.com.cn/goods/new/detail/B00001?terminal=2&gcode=' . $product->v_id . '&roomId=', [
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

            $product->stock = $data['result']['stockWithCdt']['salesNumber'];
            $product->save();

            if ($product->stock > 0) {
                $content = $app->getClient()->postJson('/cgi-bin/message/subscribe/send', [
                    'template_id' => 'oV29BXiP_LQKUdbZtSd93ce7Gl1YiYPa7y9Y_qp0n5k',
                    'page' => 'pages/visa/detail?id=' . $product->id,
                    'touser' => 'oVKEG7M1GZ3Le_9yLatRzdXRi5vk',
                    'data' => [
                        'thing1' => [
                            'value' => $product->name
                        ],
                        'time2' => [
                            'value' => now()->toDateTimeString()
                        ],
                        'number5' => [
                            'value' => $product->stock
                        ],
                        'thing3' => [
                            'value' => 'VISA一元购'
                        ]
                    ],
                    'miniprogram_state' => 'developer'
                ])->getContent();

                $this->info($content);
            }
        }
    }
}
