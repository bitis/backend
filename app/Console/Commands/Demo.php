<?php

namespace App\Console\Commands;

use App\Common\Printer\XPrinter;
use App\Models\WeBankStockRate;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Topsdk\Topapi\TopApiClient;

class Demo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected Client $client;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->client = new Client();
        $response = $this->client->get('https://personalv6.webankwealth.com/wm-htrserver/finance/product_shelf/query_product_list?user_type=0&terminal_type=Android&a=security%20info&os_ver=11&device_id=8b904e8aeb375e9a1c22b97d70510215&uid_type=qq&net_type=&session=FDF54BAABA29A135349E05BDA3F21079&target_type=0&mid=8b904e8aeb375e9a1c22b97d70510215&machine_info=GM1910&app_ver_code=901039&uid=0119D4AF73A4605FF49773551B68CA0E&app_ver=9.1.3&appid=1104566348&paid=e07a88da369db3b2ea6de9ccf304ef34-1a4cace56fd6101cbcb56606f1eb2760-d89f8d719e59b6f53752db39c0b796df&imei=&session_type=token&aid=8b904e8aeb375e9a1c22b97d70510215&wx_union_id=&channel_id=oppo_64&h5_ver=6020&oaid=&param=%7B%22page_num%22%3A1%2C%22page_size%22%3A100%2C%22shelf_type%22%3A%22MTFUND_PLUS_SUB_DETAIL%22%2C%22min_product_period%22%3Anull%2C%22max_product_period%22%3Anull%2C%22bank_short_name_list%22%3Anull%2C%22buy_with_point%22%3Anull%2C%22risk_level_list%22%3Anull%2C%22sale_status_list%22%3Anull%2C%22risk_type%22%3Anull%2C%22sort_factor%22%3A%22SMART%22%2C%22sort_desc%22%3A0%2C%22flush_quota%22%3A1%7D&', [
            'headers' => [
                'params' => '{"client_type":"APP_PRO","device_id":"8b904e8aeb375e9a1c22b97d70510215","imei":"","aid":"8b904e8aeb375e9a1c22b97d70510215","root_flag":"1","oaid":"","paid":"e07a88da369db3b2ea6de9ccf304ef34-1a4cace56fd6101cbcb56606f1eb2760-d89f8d719e59b6f53752db39c0b796df","app_sign":"C08F298C25E8402CE9179298A363ADDF","client_mac":"","ecss_type":"sm2","requestToken":"0A7D77C66BD77F65A03DC92080554016DC9270035DBE17C0C809D792328A8C38967FF6C9A","net_info":"%7B%22bssid%22%3A%2202%3A00%3A00%3A00%3A00%3A00%22%2C%22ssid%22%3A%22%3Cunknown%20ssid%3E%22%7D","csrf_token":"5AA17359947280773A3AF59AFA40A625D8364E164153006BE18DB7ED14D9BD3741826B7AB76CCD0C69E35D735CD28C5F6786D5E12980A3FA36218949CE82A16D8EE8979A7874D3FBD7D692A052CF203FC8D36D2FADC98AE76460B2E803003A0729EE1342259B75514E868BBD142EEF4F9275D605EB4CBE1E9C8B14C8662221158566725DE45A7977077F281BB5B5B0F8"}',
                'encrypt' => 'N',
                'native-gm-encrypt' => 'Y',
                'encrypt_version' => '',
                'User-Agent' => 'okhttp/3.12.1'
            ],
            'cookies' => $this->cookie(),
        ]);

        file_put_contents('stock.json', $response->getBody()->getContents());
        dd(['headers' => $response->getHeaders(), 'contents' => $response->getBody()->getContents()]);

    }

    private function time()
    {
        $response = $this->client->get('https://ldp.creditcard.ecitic.com/citiccard/lottery-gateway-pay/get-server-time.do');

        $t = json_decode($response->getBody()->getContents(), true)['resultData']['timeMillis'];

        $this->info(date('Y-m-d H:i:s', $t / 1000) . ' ' . $t % 1000);

        return $t;
    }

    private function cookie(): CookieJar
    {
        $content = 'symmetricKey=true; webankToken=0A7D77C66BD77F65A03DC92080554016DC9270035DBE17C0C809D792328A8C38967FF6C9A; userId=0999960004700174; userIdType=4; encryptedUserId={AES}5TW/ep/+LYv2r/i7dsGfq+spJv3aoAUlqkoFE4Qe3WE=; userType=1; time=Wed Apr 16 16:38:50 CST 2025; dcnNo=100; module=00000001; wechatAppId=1104566348; wechatOpenId=0119D4AF73A4605FF49773551B68CA0E; qqOpenId=0119D4AF73A4605FF49773551B68CA0E; coopOpenId=; wechatUnionId=; wechatAccessToken=; secOpenId=0119D4AF73A4605FF49773551B68CA0E; custom=1; accountTypeList=; extData=; hjCookieSign=ae04f43c8f961f7c4edb9d41d2e97a87';

        $cookies = [];

        foreach (explode('; ', $content) as $cookie) {
            list($key, $val) = explode('=', $cookie);
            $cookies[$key] = $val;
        }

        return CookieJar::fromArray($cookies, '.personalv6.webankwealth.com');
    }
}
