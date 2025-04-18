<?php

namespace App\Console\Commands\Monitor;

use App\Jobs\MiniMessageJob;
use App\Models\MiniSubscribe;
use App\Models\VisaProduct;
use EasyWeChat\MiniApp\Application;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Visa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:visa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VISA一元购库存提醒';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = VisaProduct::where('type', VisaProduct::TYPE_VISA)->get();
        $app = new Application(config('wechat.finance'));

//        foreach ($products as $product) {
//            $product->url = 'https://vtravel.link2shops.com/yiyuan/#/detail?activityId=' . $product->activityId . '&goodsId=' . $product->v_id . '&channelId=' . $product->channelId . '&platformTp=T0060';
//        }

        foreach ($products as $product) {
            $json = (new Client)->post('https://vtravel.link2shops.com/vfuliApi/api/client/ypJyActivity/goodsDetail', [
                'json' => [
                    'activityId' => $product->activityId,
                    'channelId' => $product->channelId,
                    'goodsId' => $product->v_id,
                    'source' => 'VISA',
                    'bankCd' => '',
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 NetType/WIFI MicroMessenger/7.0.20.1781(0x6700143B) WindowsWechat(0x63090c33) XWEB/11581 Flue',
                ]
            ])->getBody()->getContents();
            $p = json_decode($json, true)['goodsMap'];
            $product->update([
//                'name' => $p['name'],
//                'subtitle' => $p['subtitle'],
//                'sellPrice' => $p['sellPrice'],
//                'purchasePrice' => $p['purchasePrice'],
//                'stockStatus' => $p['stockStatus'],
//                'goodsIntroduction' => $p['goodsIntroduction'],
//                'purchaseNotes' => $p['purchaseNotes'],
//                'goodsTagOne' => $p['goodsTagOne'],
//                'goodsTagTwo' => $p['goodsTagTwo'],
                'stock' => $p['stock'],
                'updated_at' => now()
            ]);

            if ($p['stock'] > 0) {
                $subscribes = MiniSubscribe::where('product_id', $product->id)
                    ->where('type', MiniSubscribe::TYPE_LENOVO)
                    ->get();

                foreach ($subscribes as $subscribe) {
                    MiniMessageJob::dispatch(
                        'oV29BXiP_LQKUdbZtSd93ce7Gl1YiYPa7y9Y_qp0n5k',
                        $subscribe->user_id,
                        [
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
                                'value' => '数字潮人一元购'
                            ]
                        ],
                        'pages/visa/detail?id=' . $product->id
                    );
                }
            }
            sleep(2);
        }
    }
}
