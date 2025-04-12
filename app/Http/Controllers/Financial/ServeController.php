<?php

namespace App\Http\Controllers\Financial;

use App\Models\MiniUser;
use EasyWeChat\MiniApp\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class ServeController extends Controller
{
    public function serve(): ResponseInterface
    {
        $app = new Application(config('wechat.finance'));

        $server = $app->getServer();

        $message = $server->getRequestMessage(); // 原始消息
        Log::log('info', 'subscribe', (array)$message);
        $server->addEventListener('subscribe', function($message, \Closure $next) {
            Log::log('info', 'subscribe', $message);
        });

        return $server->serve();
    }
}
