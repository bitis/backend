<?php
return [
    'key' => env('JI_PUSH_APPKEY'),
    'secret' => env('JI_PUSH_SECRET'),
    'log' => env('JPUSH_LOG_PATH', storage_path('logs/jpush.log'))
];
