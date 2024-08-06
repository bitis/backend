<?php

namespace App\Console\Commands;

use App\Common\Printer\XPrinter;
use GuzzleHttp\Client;
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

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $clock = strtotime('2024-08-06 14:16:00') . '000';

        $finished = false;

        while (!$finished) {
            $now = $this->now();

            $diff = $clock - $now;

            if ($diff > 4000) {
                $this->info(date('Y-m-d H:i:s', substr($now, 0, -3)) . "\t" . substr($now, -3) . "\t" . ($clock - $now) . "\t Sleep " . ceil($diff / 2000));
                sleep(ceil($diff / 2000));
            } elseif ($now + 150 > $clock) {
                $this->info(date('Y-m-d H:i:s', substr($clock, 0, -3)) . "\t" . substr($clock, -3) . "\t" . ($clock - $now));
                $this->info(date('Y-m-d H:i:s', substr($now, 0, -3)) . "\t" . substr($now, -3) . "\t" . ($clock - $now));
                $this->confirmWord();
                $finished = true;
            } else {
                usleep(50);
            }
        }

        dd(microtime());
    }

    public function sign($params): string
    {
        $params['stamp'] = microtime();
        return Str::random(16);
    }

    public function now()
    {
        $client = new Client();

        return json_decode($client->get('http://api.m.taobao.com/rest/api3.do?api=mtop.common.getTimestamp')
            ->getBody()
            ->getContents(), true)['data']['t'];

    }

    private function confirmWord(): void
    {
        $client = new Client();

        $now = time() . rand(100, 999);
        $marketingId = '1816854086004391938';
        $round = '14:00';
        $secretword = '茉莉奶绿销量突破3000万杯';

        $response = $client->post('https://mxsa.mxbc.net/api/v1/h5/marketing/secretword/confirm', [
            'json' => [
                "marketingId" => $marketingId,
                "round" => $round,
                "secretword" => $secretword,
                "sign" => md5('marketingId=' . $marketingId . '&round=' . $round . '&s=2&secretword=' . $secretword . '&stamp=' . $now . 'c274bac6493544b89d9c4f9d8d542b84'),
                "s" => 2,
                "stamp" => $now
            ]
        ]);

        $this->line($response->getBody()->getContents());
    }
}
