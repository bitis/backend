<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\OfficialAccountConfig;
use App\Models\User;
use App\Models\WechatUser;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Messages\Text;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;

class WechatController extends Controller
{
    /**
     * 公众号
     *
     * @param $account
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function official($account): Response
    {
        $config = OfficialAccountConfig::where('account', $account)->first();

        $app = Factory::officialAccount($config);

        $app->server->push(function ($message) use ($app, $account) {
            $FromUserName = $message["FromUserName"];

            $content = "";
            switch ($message['MsgType']) {
                case 'event':
                    $payload = '';
                    $user = $app->user->get($FromUserName);
                    switch ($message['Event']) {
                        case 'subscribe':
                            $content = "感谢您关注！";
                            WechatUser::updateOrCreate(['openid' => $FromUserName], ['subscribe' => 1, 'unionid' => $user['unionid']]);
                            $payload = $message['EventKey'] ? substr($message['EventKey'], '8') : ''; // 移除前缀
                            break;
                        case 'unsubscribe':
                            WechatUser::where('openid', $FromUserName)->update(['subscribe' => 1]);
                            break;
                        case 'SCAN':
                            $payload = $message['EventKey'];
                            break;
                    }

                    if ($payload) {
                        $this->handle($user, json_decode($payload, true));
                    }
                    break;
                case 'text':
                    if ($message['Content'] == 'openid') $content = "openid:" . $FromUserName;
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

    private function handle($user, $message): void
    {
        switch ($message['k']) {
            case 'member-bind':
                Member::where('id', $message['v'])->update([
                    'openid' => $user['openid'],
                    'unionid' => empty($user['unionid']) ? null : $user['unionid'],
                ]);
                break;
            case 'staff-bind':
                User::where('id', $message['v'])->update([
                    'openid' => $user['openid'],
                    'unionid' => empty($user['unionid']) ? null : $user['unionid'],
                ]);
                break;
        }
    }
}
