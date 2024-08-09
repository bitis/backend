<?php
return [
    'xprinter' => [
        'user' => env('X_PRINTER_ID', 'test'),
        'key' => env('X_PRINTER_SECRET', 'test'),
        'debug' => env('PRINTER_DEBUG', 0)
    ]
];
