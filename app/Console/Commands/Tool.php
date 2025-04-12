<?php

namespace App\Console\Commands;

use EasyWeChat\MiniApp\Application;
use Illuminate\Console\Command;

class Tool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tool';

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
        $app = new Application(config('wechat.finance'));
        $response = $app->getClient()->postJson('/cgi-bin/message/subscribe/send', [
            'template_id' => 'oV29BXiP_LQKUdbZtSd93ce7Gl1YiYPa7y9Y_qp0n5k',
            'page' => 'pages/index/index',
            'touser' => 'oVKEG7M1GZ3Le_9yLatRzdXRi5vk',
            'data' => [
                'thing1' => [
                    'value' => 'test'
                ],
                'time2' => [
                    'value' => now()->toDateTimeString()
                ],
                'number5' => [
                    'value' => 99
                ],
                'thing3' => [
                    'value' => 'VISA一元购'
                ]
            ],
            'miniprogram_state' => 'developer'
        ]);
        dd($response->getContent());
    }
}
