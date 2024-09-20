<?php

namespace App\Http\Controllers\Store;

use Alipay\EasySDK\Kernel\Payment as AlipayPayment;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\StoreOrder;
use EasyWeChat\Payment\Application as WechatPayment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return success(Price::all());
    }

    public function order(Request $request, AlipayPayment $alipay, WechatPayment $wechat): JsonResponse
    {
        $id = $request->input('id');

        $is_app = $request->input('is_app');

        $payment_channel = $request->input('payment_channel');

        $price = Price::find($id);

        if (!$price) return fail();

        $order = StoreOrder::create([
            'order_no' => Str::uuid(),
            'store_id' => $this->store_id,
            'price' => $price->price,
            'original_price' => $price->original_price,
            'forever' => $price->forever,
            'month' => $price->month,
            'name' => $price->name,
            'payment_channel' => $payment_channel,
        ]);

        if ($payment_channel == 'alipay') {
            try {
                if ($is_app) {
                    $result = $alipay->faceToFace()
                        ->asyncNotify(route('order.price.alipay_notify'))
                        ->preCreate(
                            $order->name,
                            $order->order_no,
                            $order->price,
                        );
                } else {
                    $result = $alipay->wap()
                        ->asyncNotify(route('order.price.alipay_notify'))
                        ->pay(
                            $order->name,
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
                'body' => $order->name,
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

    public function alipayNotify(Request $request, AlipayPayment $alipay): string
    {
        $parameters = $request->all();

        if (!$alipay->common()->verifyNotify($parameters)) return 'fail';

        if ($parameters['trade_status'] == 'TRADE_SUCCESS') {
            $order = StoreOrder::where('order_no', $parameters['out_trade_no'])->first();
            if (!$order or $order->paid_at) return 'success';
            $order->afterPayment();
        }

        return 'success';
    }

    public function wechatNotify(WechatPayment $wechat): Response
    {
       return $wechat->handlePaidNotify(function ($message) {
            $order = StoreOrder::where('order_no', $message['out_trade_no'])->first();
            if (!$order or $order->paid_at) return;
            $order->afterPayment();
        });
    }
}
