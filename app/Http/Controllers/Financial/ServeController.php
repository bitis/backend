<?php

namespace App\Http\Controllers\Financial;

use Closure;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class ServeController extends Controller
{
    public function serve(): ResponseInterface
    {
        $app = new MiniApp(config('wechat.finance'));

        $server = $app->getServer();

        $message = $server->getRequestMessage(); // 原始消息
        Log::log('info', 'subscribe', (array)$message);
        $server->addEventListener('subscribe', function ($message, Closure $next) {
            Log::log('info', 'subscribe', $message);
        });

        return $server->serve();
    }

    public function official()
    {
        $app = new OfficialAccount(config('wechat.finance'));

        $server = $app->getServer();

        $message = $server->getRequestMessage(); // 原始消息
        Log::log('info', 'subscribe', (array)$message);
        $server->addEventListener('subscribe', function ($message, Closure $next) {
            Log::log('info', 'subscribe', $message);

            return '感谢您关注!';
        });

        return $server->serve();
    }
}
