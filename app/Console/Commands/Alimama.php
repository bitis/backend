<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;

class Alimama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mama';

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

        $this->get();
    }

    public function get()
    {
        $_tb_token_ = 'e73768831337';

        $cookies = [
            '_tb_token_' => $_tb_token_,
            't_alimama' => 'a8c5397544c15fb482a51625dd95039f',
            'cookie2_alimama' => '159ce0ecd4b5c6d324491e8758e74fd7',
        ];

        $response = $this->client->get('https://pub.alimama.com/openapi/param2/1/gateway.unionpub/xt.entry.json', [
            'headers' => [
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36Content-Type: application/json',
                'referer' => 'https://pub.alimama.com/portal/v2/tool/links/page/home/index.htm?mode=1'
            ],
            'cookies' => CookieJar::fromArray($cookies, '.alimama.com'),
            'query' => [
                't' => now()->getTimestampMs(),
                '_tb_token_' => $_tb_token_,
                'floorId' => '61446',
                'refpid' => 'mm_108233749_3161750457_115832050372',
                'variableMap' => json_encode([
                    "url" => '3Fu😊zhi3$DzfW3FxWulO$:// CA1831,打開/',
                    "union_lens" => "b_pvid:a219t._portal_v2_tool_links_page_home_index_htm_1733290918037_5044558789532394_WTLLH",
                    "lensScene" => "PUB",
                    "spmB" => "_portal_v2_tool_links_page_home_index_htm"
                ])
            ]
        ]);

        $this->info('Result: ' . $response->getBody()->getContents());
    }

}
