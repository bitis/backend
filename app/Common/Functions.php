<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Alipay\EasySDK\Kernel\Config as AlipayConfig;
function fail($msg = 'FAIL', $code = -1): JsonResponse
{
    return response()->json([
        'code' => $code,
        'msg' => $msg,
        'data' => null
    ]);
}

function success($data = null): JsonResponse
{
    if ($data instanceof LengthAwarePaginator) {
        return response()->json([
            'code' => 0,
            'msg' => 'OK',
            'data' => [
                'list' => $data->items(),
                'total' => $data->total(),
            ]
        ]);
    }

    return response()->json([
        'code' => 0,
        'msg' => 'OK',
        'data' => $data
    ]);
}

function getPerPage(): int
{
    return request()->input('pageSize') ?: config('default.pageSize');
}

function getAlipayConfig(): AlipayConfig
{
    $config = config('payment.alipay');

    $options = new AlipayConfig();
    $options->protocol = 'https';
    $options->gatewayHost = 'openapi.alipay.com';
    $options->signType = 'RSA2';

    $options->appId = $config['app_id'];

    // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
    $options->merchantPrivateKey = $config['merchant_private_key'];

    $options->alipayCertPath = '<-- 请填写您的支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt -->';
    $options->alipayRootCertPath = '<-- 请填写您的支付宝根证书文件路径，例如：/foo/alipayRootCert.crt" -->';
    $options->merchantCertPath = '<-- 请填写您的应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt -->';

    //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
    // $options->alipayPublicKey = '<-- 请填写您的支付宝公钥，例如：MIIBIjANBg... -->';

    return $options;
}

function getWechatPayConfig() {
    return config('payment.wechat');
}
