<?php

namespace App\Http\Controllers;

use App\Models\OfficialAccountConfig;
use App\Models\WechatUser;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use Symfony\Component\HttpFoundation\Response;

class WechatController extends Controller
{
    /**
     * 公众号
     *
     * @param $account
     * @return Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function official($account): Response
    {
        $config = OfficialAccountConfig::where('account', $account)->first();

        $app = Factory::officialAccount($config);

        $app->server->push(function ($message) use ($app, $account) {
            $FromUserName = $message["FromUserName"];

            $FromUser = $app->user->get($FromUserName);
            $content = "";
            switch ($message['MsgType']) {
                case 'event':
                    $eventKey = $message['EventKey'];
                    switch ($message['Event']) {
                        case 'subscribe':
                            $content = "感谢您关注！";
                            WechatUser::updateOrCreate(['openid' => $FromUserName], ['subscribe' => 1]);
                            break;
                        case 'unsubscribe':
                            WechatUser::where('openid', $FromUserName)->update(['subscribe' => 1]);
                            break;
                        case 'SCAN':
                            break;
                        default:
                            break;
                    }
                    break;
                case 'text':
                    if ($message['Content'] == 'openid') $content = "openid:" . $message['FromUserName'] . "\n unionid:" . isset($FromUser['unionid']) ? $FromUser['unionid'] : '';
                    break;
                default:
                    break;
            }
            if ($content != "") {
                $app->customer_service->message(new Text($content))->to($FromUserName)->send();
            }
        });

        return $app->server->serve();
    }
}
