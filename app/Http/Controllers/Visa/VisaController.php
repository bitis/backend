<?php

namespace App\Http\Controllers\Visa;

use App\Http\Controllers\Controller;
use App\Models\VisaProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisaController extends Controller
{

    /**
     * 产品列表
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return success(VisaProduct::all());
    }

    /**
     * 订阅消息
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe(Request $request): JsonResponse
    {
        $id = $request->input('id');

        return success();
    }
}
