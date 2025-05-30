<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CloudFile;
use App\Models\SmsConfig;
use App\Models\SmsDetail;
use App\Models\SmsLog;
use App\Models\SmsOrder;
use App\Models\SmsPackage;
use App\Models\SmsRecord;
use App\Models\SmsSignature;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SmsOrderController extends Controller
{

    public function packages(): JsonResponse
    {
        return success(SmsPackage::all());
    }

    public function order(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $payment_channel = $request->input('payment_channel');

        $package = SmsPackage::find($id);
        if (!$package) {
            return fail('找不到该套餐');
        }

        if ($payment_channel == 'alipay') {
            return $this->alipay($package);
        } elseif ($payment_channel == 'wechat') {
            return $this->wechat($package);
        }

        $order = SmsOrder::create([
            'store_id' => $this->store_id,
            'package_id' => $id,
            'order_no' => Str::random(16),
            'name' => $package->name,
            'number' => $package->number,
            'price' => $package->price,
            'payment_channel' => $payment_channel
        ]);

        return success($order);
    }

    public function notify()
    {

    }

    private function alipay($package): JsonResponse
    {
        return success();
    }

    private function wechat($package): JsonResponse
    {
        return success();
    }
}
