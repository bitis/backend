<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\Register;
use App\Models\Enumerations\UserStatus;
use App\Models\Store;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function setStockWarning(Request $request)
    {

    }

    public function getStockWarning(Request $request)
    {
        $store = $request->user()->store;
    }
}
