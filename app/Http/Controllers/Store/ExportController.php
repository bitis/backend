<?php

namespace App\Http\Controllers\Store;

use Alipay\EasySDK\Kernel\Payment as AlipayPayment;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use App\Http\Controllers\Controller;
use App\Models\ExportOrder;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Payment\Application as WechatPayment;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function index(): JsonResponse
    {
        return success();
    }

    /**
     * 购买服务
     *
     * @param Request $request
     * @param AlipayPayment $alipay
     * @param WechatPayment $wechat
     * @return JsonResponse
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function order(Request $request, AlipayPayment $alipay, WechatPayment $wechat): JsonResponse
    {
        $is_app = $request->input('is_app');
        $payment_channel = $request->input('payment_channel');

        $name = '会员导出服务 x1';

        $order = ExportOrder::create([
            'store_id' => $this->store_id,
            'order_no' => ExportOrder::generateNumber(),
            'price' => '9.98',
            'original_price' => '18.80',
            'status' => ExportOrder::STATUS_UNPAID,
        ]);

        if ($payment_channel == 'alipay') {
            try {
                if ($is_app) {
                    $result = $alipay->faceToFace()
                        ->asyncNotify(route('order.price.alipay_notify'))
                        ->preCreate(
                            $name,
                            $order->order_no,
                            $order->price,
                        );
                } else {
                    $result = $alipay->wap()
                        ->asyncNotify(route('order.price.alipay_notify'))
                        ->pay(
                            $name,
                            $order->order_no,
                            $order->price,
                            '',
                            '',
                        );
                }
                $responseChecker = new ResponseChecker();
                if ($responseChecker->success($result)) {
                    return success([
                        'channel' => 'alipay',
                        'result' => $result
                    ]);
                } else {
                    return fail($result->msg . "，" . $result->subMsg);
                }
            } catch (Exception $e) {
                report($e);
                return fail($e->getMessage());
            }
        } else {
            $result = $wechat->order->unify([
                'body' => $name,
                'out_trade_no' => $order->order_no,
                'total_fee' => $order->price * 100,
                'notify_url' => route('order.price.wechat_notify'),
                'trade_type' => $is_app ? 'NATIVE' : 'APP',
                'openid' => '',
                'sign_type' => 'MD5',
            ]);

            if ($is_app)
                $result = $request->getSchemeAndHttpHost() . "/qrcode?code={$result['code_url']}";
            else {
                $result = $wechat->jssdk->appConfig($result['prepay_id']);
            }

            success([
                'channel' => 'alipay',
                'result' => $result
            ]);
        }

        return success($order);
    }

    /**
     * 导出历史
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        $orders = ExportOrder::where('store_id', $this->store_id)->paginate(getPerPage());

        return success($orders);
    }

    /**
     * 支付宝回调
     *
     * @param Request $request
     * @param AlipayPayment $alipay
     * @return string
     */
    public function alipayNotify(Request $request, AlipayPayment $alipay): string
    {
        $parameters = $request->all();

        if (!$alipay->common()->verifyNotify($parameters)) return 'fail';

        if ($parameters['trade_status'] == 'TRADE_SUCCESS') {
            ExportOrder::paid($parameters['out_trade_no'], 'alipay', $parameters['out_trade_no']);
        }

        return 'success';
    }

    /**
     * 微信会回调
     *
     * @param WechatPayment $wechat
     * @return Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function wechatNotify(WechatPayment $wechat): Response
    {
        return $wechat->handlePaidNotify(function ($message) {
            ExportOrder::paid($message['out_trade_no'], 'wechat', $message['out_trade_no']);
        });
    }
}
