<?php

namespace App\Console\Commands;

use App\Common\DingTalk;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;

class Pa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pa';

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

        $timer = strtotime('2024-08-22 18:00:00');

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
        $response = $this->client->post('https://rmb.pingan.com.cn/credit/core/cust/ma/pabank/getSessionStatus', [
            'headers' => $this->headers(),
            'cookies' => $this->cookie(),
            'form_params' => $this->form()
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if ($result['code'] != '0000') {
            $this->error($result['msg']);
            die();
        }
        if ($result['body']['userStatus'] != 2) {
            $this->error($result['body']['userStatus']);
            die();
        }

        $this->info('Session Status: ' . $result['body']['userStatus']);
    }

    public function kill()
    {
//        $this->info('秒杀中...' . $this->time());
        $response = $this->client->post('https://rmb.pingan.com.cn/credit/core/cust/ma/online/pabank/ma/pm/others/seckill/doKill', [
            'headers' => $this->headers(),
            'cookies' => $this->cookie(),
            'form_params' => $this->form()
        ]);

        $this->info('秒杀结果: ' . $response->getBody()->getContents());

        die();
    }

    public function form(): array
    {
        return [
            'serviceVersion' => '1.0',
            'osType' => 1,
            'channel' => 1,
            'version' => '1.5.0',
            'random' => '0.' . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999),
            'webTagInfo' => 'module=8point8,token=P14562324082215562599780,lastToken=99780',
            'addWebTagInfoList[]' => 'module=8point8'
        ];
    }

    private function cookie(): CookieJar
    {
        $content = 'fp_ver=4.7.9; BSFIT4_EXPIRATION=1693629040109; BSFIT4_OkLJUJ=JHlPd-lgeOq8InFzZb314DaGArPZodmi; BSFIT4_DEVICEID=gdHduivBMVcBUTbFi8fPw41kDgObqu1joFTLZBjvfLnR-ZPOM1L-HTUFCCFksrIMcz2sdw-dp85X4DGgHD8myBm7jPI31Hbq3auerWQRmTMPirgI3ojvyc81CRJiurLBlqFLvU0wI9q9LNHYAniIc5cOFWlPDEWe; WEBTRENDS_ID=cf56a43e-c225-4087-b3c7-8d3849767939; WEBTRENDS_SESSIONID=d279b78d-f5b1-4d21-8919-91a2e3f4abc1; shuntid=%7B%7D; is_logon=-1; x-g-vid=yI27/foU00405g6huhp1+M; WT-H5-PAGE-CACHE=%7B%22divID%22%3A%22cf56a43e-c225-4087-b3c7-8d3849767939%22%2C%22wxno%22%3A%22gh_a977c8acfae7%22%2C%22openid%22%3A%22oiBF4jiGhr7WYrKq7psRmYNXQ5BE%22%2C%22unionid%22%3A%22oIBh4uMz9GqOkzbe5F2rCQ5scvt8%22%2C%22is_logon%22%3A%22-1%22%2C%22outerid%22%3A%22ou0005460%22%2C%22cid%22%3A%22ci1000022%22%2C%22last_innerid%22%3A%22KDAPP-XYKPD2022-DBDFWQ-1%22%2C%22source%22%3A%22all%22%2C%22campaignid%22%3A%22202203027317%22%2C%22usertagid%22%3A%22KDPT-3-445180%22%2C%22strategyid%22%3A%22CC_W324565_U3-445180_A_C10342%22%2C%22last_traceid%22%3A%2250030_9mDqhKF597j%22%2C%22dcsdat%22%3A1724313441780%2C%22vid%22%3A%22yI27%2FfoU00405g6huhp1%2BM%22%2C%22pagetitle%22%3A%22%E5%AE%89%E5%85%A8%E9%AA%8C%E8%AF%81%E5%8D%A1%E5%AF%86%EF%BC%88H5%E9%AA%8C%E8%AF%81%E7%A0%81%E7%99%BB%E5%BD%95%EF%BC%89%22%2C%22pageID%22%3A%22P138114%22%2C%22PUI_ELEMENT_TRACE%22%3A%22%7B%5C%22total%5C%22%3A4%2C%5C%22version%5C%22%3A%5C%225.0.34%5C%22%2C%5C%22cell%5C%22%3A1%2C%5C%22icon%5C%22%3A1%2C%5C%22securepopup%5C%22%3A1%2C%5C%22button%5C%22%3A1%7D%22%2C%22pageurl%22%3A%22https%3A%2F%2Fb.pingan.com.cn%2Fibank%2Fmember%2Fv3%2Fw%2Fbind-device%2Fcard-verify.html%22%2C%22pagequery%22%3A%22%3Ffrom%3DwechatH5%26lastLogin%3DwechatH5%22%2C%22last_page_id%22%3A%22P138113%22%7D; brcpSessionTicket=Sc13da51a0e9649379180dac1d259730c621bcff; PAEBANK_PARAM_W={"outerid":"ou0005460","cid":"ci1000022"}; sdc_PABankParam=WT.source%3Dall%26WT.innerid%3DKDAPP-XYKPD2022-DBDFWQ-1%26WT.campaignid%3D202203027317%26WT.usertagid%3DKDPT-3-445180%26WT.strategyid%3DCC_W324565_U3-445180_A_C10342%26WT.traceid%3D50030_9mDqhKF597j%26WT.activity_FlowId%3Dm_B0P5v5UcNEW6KCzI4502; PAEBANK_PARAM_N={"source":"all","innerid":"KDAPP-XYKPD2022-DBDFWQ-1","campaignid":"202203027317","usertagid":"KDPT-3-445180","strategyid":"CC_W324565_U3-445180_A_C10342","traceid":"50030_9mDqhKF597j"}; last_page_id=P145623; PAEBANK_PARAM=%7B%22outerid%22%3A%22ou0005460%22%2C%22cid%22%3A%22ci1000022%22%2C%22source%22%3A%22all%22%2C%22innerid%22%3A%22KDAPP-XYKPD2022-DBDFWQ-1%22%2C%22campaignid%22%3A%22202203027317%22%2C%22usertagid%22%3A%22KDPT-3-445180%22%2C%22strategyid%22%3A%22CC_W324565_U3-445180_A_C10342%22%2C%22traceid%22%3A%2250030_9mDqhKF597j%22%2C%22timetag%22%3A1724313450016%2C%22deviceid%22%3A%22gdHduivBMVcBUTbFi8fPw41kDgObqu1joFTLZBjvfLnR-ZPOM1L-HTUFCCFksrIMcz2sdw-dp85X4DGgHD8myBm7jPI31Hbq3auerWQRmTMPirgI3ojvyc81CRJiurLBlqFLvU0wI9q9LNHYAniIc5cOFWlPDEWe%22%2C%22webtrendsid%22%3A%22cf56a43e-c225-4087-b3c7-8d3849767939%22%2C%22sessionid%22%3A%22d279b78d-f5b1-4d21-8919-91a2e3f4abc1%22%2C%22openid%22%3A%22oiBF4jiGhr7WYrKq7psRmYNXQ5BE%22%2C%22wxno%22%3A%22gh_a977c8acfae7%22%7D; mobilegray=%5B%5D';

        $cookies = [];

        foreach (explode('; ', $content) as $cookie) {
            list($key, $val) = explode('=', $cookie);
            $cookies[$key] = $val;
        }

        return CookieJar::fromArray($cookies, '.rmb.pingan.com.cn');
    }

    private function headers(): array
    {
        $content = 'Host: rmb.pingan.com.cn
Content-Length: 197
Accept: application/json
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 NetType/WIFI MicroMessenger/7.0.20.1781(0x6700143B) WindowsWechat(0x63090b19) XWEB/11205 Flue
Content-Type: application/x-www-form-urlencoded;charset=UTF-8
Origin: https://b.pingan.com.cn
Sec-Fetch-Site: same-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: https://b.pingan.com.cn/
Accept-Encoding: gzip, deflate, br
Accept-Language: zh-CN,zh;q=0.9';
        $headers = [];

        foreach (explode("\n", $content) as $header) {
            list($key, $val) = explode(': ', $header);
            $headers[$key] = $val;
        }
        return $headers;
    }

    private function time()
    {
        $response = $this->client->get('http://api.m.taobao.com/rest/api3.do?api=mtop.common.getTimestamp');

        $t = json_decode($response->getBody()->getContents(), true)['data']['t'];

        $this->info(date('Y-m-d H:i:s', $t / 1000) . '+' . $t % 1000);

        return $t;
    }
}
