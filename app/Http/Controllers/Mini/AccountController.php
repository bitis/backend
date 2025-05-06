<?php

namespace App\Http\Controllers\Mini;

use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function index(): JsonResponse
    {
        return success($this->user);
    }
}
