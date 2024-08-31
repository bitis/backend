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

        $timer = strtotime('2024-08-29 18:00:00');

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
            'webTagInfo' => 'module=8point8,token=P14562324082917133183308,lastToken=83308',
            'addWebTagInfoList[]' => 'module=8point8'
        ];
    }

    private function cookie(): CookieJar
    {
        $content = 'fp_ver=4.7.9; BSFIT4_EXPIRATION=1693629040109; BSFIT4_OkLJUJ=JHlPd-lgeOq8InFzZb314DaGArPZodmi; BSFIT4_DEVICEID=gdHduivBMVcBUTbFi8fPw41kDgObqu1joFTLZBjvfLnR-ZPOM1L-HTUFCCFksrIMcz2sdw-dp85X4DGgHD8myBm7jPI31Hbq3auerWQRmTMPirgI3ojvyc81CRJiurLBlqFLvU0wI9q9LNHYAniIc5cOFWlPDEWe; shuntid=%7B%7D; WEBTRENDS_ID=9021248c-66d8-4e40-96ab-138add901798; x-g-vid=scCuPSF00040906hD3MIqw; JSESSIONID=CA6FF114DF9C4CE7A24754500B4542C0; WEBTRENDS_SESSIONID=117050ef-763b-44dd-83cc-6010bff5b0c4; mobilegray=%5B%5D; PAEBANK_PARAM_W={"outerid":"ou0005460","cid":"ci1000022"}; sdc_PABankParam=WT.source%3Dall%26WT.innerid%3Dkdapp.LSWJ-SY20BJX.MODULE-KQMK~477923930816909312.xxx.3%26WT.campaignid%3D202104019532%26WT._bid_id%3Deva3kvffpx3p_100217448%26WT.traceid%3D50030_1pufVsDik95%26WT.activity_FlowId%3Dm_U0P6TOGeKcZ2QXBr4502; PAEBANK_PARAM_N={"source":"all","innerid":"kdapp.LSWJ-SY20BJX.MODULE-KQMK~477923930816909312.xxx.3","campaignid":"202104019532","_bid_id":"eva3kvffpx3p_100217448","traceid":"50030_1pufVsDik95"}; last_page_id=P145623; is_logon=-1; brcpSessionTicket=S9195a6a63877489e9472a776c763296c27274d8; PAEBANK_PARAM=%7B%22outerid%22%3A%22ou0005460%22%2C%22cid%22%3A%22ci1000022%22%2C%22source%22%3A%22all%22%2C%22innerid%22%3A%22kdapp.LSWJ-SY20BJX.MODULE-KQMK~477923930816909312.xxx.3%22%2C%22campaignid%22%3A%22202104019532%22%2C%22_bid_id%22%3A%22eva3kvffpx3p_100217448%22%2C%22traceid%22%3A%2250030_1pufVsDik95%22%2C%22timetag%22%3A1724922229960%2C%22deviceid%22%3A%22gdHduivBMVcBUTbFi8fPw41kDgObqu1joFTLZBjvfLnR-ZPOM1L-HTUFCCFksrIMcz2sdw-dp85X4DGgHD8myBm7jPI31Hbq3auerWQRmTMPirgI3ojvyc81CRJiurLBlqFLvU0wI9q9LNHYAniIc5cOFWlPDEWe%22%2C%22webtrendsid%22%3A%229021248c-66d8-4e40-96ab-138add901798%22%2C%22sessionid%22%3A%22117050ef-763b-44dd-83cc-6010bff5b0c4%22%2C%22openid%22%3A%22oiBF4jiGhr7WYrKq7psRmYNXQ5BE%22%2C%22wxno%22%3A%22gh_a977c8acfae7%22%7D';

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
