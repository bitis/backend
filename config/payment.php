<?php
return [
    'alipay' => [
        'app_id' => env('ALIPAY_APP_ID'),
        'merchant_private_key' => env('ALIPAY_MERCHANT_PRIVATE_KEY'),
    ],
    'wechat' => [
        'sandbox' => env('WECHAT_PAYMENT_SANDBOX', false),
        'app_id' => env('WECHAT_PAYMENT_APPID'),
        'secret' => '9fc804af23ab2cc75ebbf9e7990fed15',
        'mch_id' => env('WECHAT_PAYMENT_MCH_ID'),
        'key' => env('WECHAT_PAYMENT_KEY'),
        'cert_path' => env('WECHAT_PAYMENT_CERT_PATH'),    // XXX: 绝对路径！！！！
        'key_path' => env('WECHAT_PAYMENT_KEY_PATH'),      // XXX: 绝对路径！！！！
        'notify_url' => env('WECHAT_PAYMENT_NOTIFY_URL'),  // 默认支付结果通知地址
        'log' => [
            'default' => 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
            'channels' => [
                // 测试环境
                'dev' => [
                    'driver' => 'single',
                    'path' => '/tmp/easywechat.log',
                    'level' => 'debug',
                ],
                // 生产环境
                'prod' => [
                    'driver' => 'daily',
                    'path' => storage_path('logs/easywechat.log'),
                    'level' => 'info',
                ],
            ],
        ],
    ]
];
