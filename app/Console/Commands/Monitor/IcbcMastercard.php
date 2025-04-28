<?php

namespace App\Console\Commands\Monitor;

use App\Jobs\MiniMessageJob;
use App\Models\MiniSubscribe;
use App\Models\VisaProduct;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class IcbcMastercard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:icbc_mastercard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '工行万事达一元购';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = VisaProduct::where('type', VisaProduct::TYPE_ICBC_MASTERCARD)->pluck('v_id');

        $json = (new Client())->post('https://bcsscs.icbc.com.cn/api-goods/config/goods/recommend/list?corpId=2000001795', [
            'json' => [
                'corpId' => '2000001795',
                'goodsNoArr' => $products,
                'activityNoArr' => []
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 NetType/WIFI MicroMessenger/7.0.20.1781(0x6700143B) WindowsWechat(0x63090c33) XWEB/11581 Flue',
            ]
        ])->getBody()->getContents();
        $list = json_decode($json, true)['data']['data'];

        foreach ($list as $item) {
            $product = VisaProduct::where('type', VisaProduct::TYPE_ICBC_MASTERCARD)
                ->where('v_id', $item['goodsNo'])->first();
            $product->update([
                'stock' => $item['storageStatus'] ? 1 : 0,
                'updated_at' => now()
            ]);

            if ($product['stock']) {
                $subscribes = MiniSubscribe::where('product_id', $product->id)
                    ->where('type', MiniSubscribe::TYPE_ICBC_MASTERCARD)
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
                                'value' => '记得回来点几下订阅按钮，不然下次就收不到了'
                            ]
                        ],
                        'pages/visa/detail?id=' . $product->id
                    );
                }
                sleep(2);
            }
        }

    }
}
