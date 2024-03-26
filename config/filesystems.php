<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'oss' => [
            'driver' => 'oss',
            "bucket" => env('OSS_BUCKET'),
            "endpoint" => env('OSS_ENDPOINT'),
            "url" => env('OSS_URL'),
            "access_key_id" => env('OSS_ACCESS_KEY_ID'),
            "access_key_secret" => env('OSS_ACCESS_KEY_SECRET'),
        ],

        'qcloud' => [
            'driver' => 'qcloud',
            'app_id' => env('QCLOUD_COS_APP_ID'),
            'secret_id' => env('QCLOUD_SECRET_ID'),
            'secret_key' => env('QCLOUD_SECRET_KEY'),
            'url' => env('QCLOUND_URL'),
            'region' => 'ap-shanghai',
            'bucket' => 'xinglan',

            // 可选，如果 bucket 为私有访问请打开此项
            'signed_url' => false,

            // 可选，是否使用 https，默认 false
            'use_https' => true,

            // 可选，自定义域名
//            'domain' => 'emample-12340000.cos.test.com',

            // 可选，使用 CDN 域名时指定生成的 URL host
//            'cdn' => 'https://youcdn.domain.com/',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
