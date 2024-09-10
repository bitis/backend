<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    /**
     * 门店资料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detail(Request $request): JsonResponse
    {
        return success($request->user()->store);
    }


    /**
     * 编辑资料
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function form(Request $request): JsonResponse
    {
        $store = $request->user()->store;

        $store->fill($request->except([
            'official_account_qrcode',
            'expiration_date'
        ]));

        $store->save();

        return success();
    }
}
