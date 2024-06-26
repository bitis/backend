<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Enumerations\PaymentType;
use App\Models\Industry;
use App\Models\PaymentChannel;
use Illuminate\Http\JsonResponse;

class CommonController extends Controller
{
    /**
     * 省市区数据
     *
     * @return JsonResponse
     */
    public function area(): JsonResponse
    {
        return success(Area::with('children', 'children.children')->where('pid', 0)->get());
    }

    /**
     * 行业数据
     *
     * @return JsonResponse
     */
    public function industry(): JsonResponse
    {
        return success(Industry::with('children')->where('pid', 0)->get());
    }

    public function config(): JsonResponse
    {
        return success([
            'payment_types' => PaymentChannel::all(),
        ]);
    }
}
