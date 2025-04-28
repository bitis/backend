<?php

namespace App\Jobs;

use App\Models\MiniUser;
use EasyWeChat\MiniApp\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MiniMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Application $app;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $template_id, protected $uid, protected $message, protected $page = '')
    {
        $this->app = new Application(config('wechat.finance'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = MiniUser::find($this->uid);

        try {
            $response = $this->app->getClient()->postJson('/cgi-bin/message/subscribe/send', [
                'template_id' => $this->template_id,
                'page' => $this->page,
                'touser' => $user->openid,
                'data' => $this->message,
            ])->getContent();
            Log::info('MSG', json_decode($response, true));
        } catch (\Exception $e) {
            $this->fail($e);
        }

    }
}
