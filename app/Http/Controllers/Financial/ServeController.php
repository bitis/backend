<?php

namespace App\Http\Controllers\Financial;

use Closure;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OfficialAccount\Message;
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

        $server->addEventListener('subscribe', function (Message $message, Closure $next) {
            Log::log('info', 'subscribe', $message->toArray());

            return '感谢您关注!';
        });

        $server->addEventListener('text', function (Message $message, Closure $next) {
            Log::log('info', 'text', $message->toArray());

            if ($message->getOriginalContents() == 'openid') {
                return $message->FromUserName;
            }

            return '感谢您关注!';
        });

        return $server->serve();
    }
}
