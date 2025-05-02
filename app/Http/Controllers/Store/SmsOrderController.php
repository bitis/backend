<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CloudFile;
use App\Models\SmsConfig;
use App\Models\SmsDetail;
use App\Models\SmsLog;
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

        return success();
    }

    public function notify()
    {

    }

    private function alipay($package)
    {
        return success();
    }

    private function wechat($package)
    {
        return success();
    }
}
