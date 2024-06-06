<?php

namespace App\Common;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class DingTalk
{
    public function __construct()
    {
    }

    public static function send($title, $message, $atMobiles = [], $isAtAll = false): void
    {
        $timestamp = time() * 1000;
        $secret = config('dingtalk.secret');

        $sign = urlencode(base64_encode(hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true)));

        $dingtalk = "https://oapi.dingtalk.com/robot/send?access_token=" . config('dingtalk.access_token')
            . "&timestamp=" . $timestamp . "&sign=" . $sign;

        (new Client)->post($dingtalk, [
            'json' => [
                "msgtype" => "markdown",
                "at" => [
                    "atMobiles" => [],
                    "isAtAll" => true
                ],
                "markdown" => [
                    "title" => $title,
                    "text" => $message,
                ]
            ]
        ]);

    }
}
