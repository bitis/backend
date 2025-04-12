<?php

namespace App\Http\Controllers\Financial;

use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function index(): JsonResponse
    {
        return success($this->user);
    }
}
