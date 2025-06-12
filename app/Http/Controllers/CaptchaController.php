<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Mews\Captcha\Captcha;

class CaptchaController extends Controller
{
    public function create(Captcha $captcha, string $config = 'default'): JsonResponse
    {
        return success($captcha->create($config, true));
    }
}
