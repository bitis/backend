<?php
return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 3.0,

    'expiration' => env('SMS_CODE_EXPIRATION', 5),

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'qcloud'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'aliyun' => [
            'access_key_id' => env('ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => env('ALIYUN_SIGN_NAME'),
        ],
        'qcloud' => [
            'sdk_app_id' => env('QCLOUD_SMS_APP_ID'), // 短信应用的 SDK APP ID
            'secret_id' => env('QCLOUD_SECRET_ID'), // SECRET ID
            'secret_key' => env('QCLOUD_SECRET_KEY'), // SECRET KEY
            'sign_name' => env('QCLOUD_SIGN_NAME'), // 短信签名
        ],
    ],
];
