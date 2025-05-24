<?php

namespace App\Console\Commands;

use App\Common\Filter;
use App\Common\Printer\XPrinter;
use App\Models\Member;
use App\Models\WeBankStockRate;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        echo Str::random(16);
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
