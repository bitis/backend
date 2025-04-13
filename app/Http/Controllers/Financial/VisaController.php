<?php

namespace App\Http\Controllers\Financial;

use App\Models\MiniCoinLog;
use App\Models\VisaProduct;
use App\Models\MiniSubscribe;
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

    public function detail(Request $request): JsonResponse
    {
        $product = VisaProduct::find($request->input('id'));
        $product->is_subscribe = MiniSubscribe::where('product_id', $product->id)->where('user_id', $this->user->id)->exists();
        return success($product);
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
        $type = $request->input('type');

        if (empty($id)) {
            return fail('订阅失败');
        }

        if (!$this->user) return fail('请重新打开小程序');

        if (MiniSubscribe::where([
            'product_id' => $id,
            'user_id' => $this->user->id,
            'type' => $type
        ])->exists()
        ) return fail('您已经订阅过了');

        $product = VisaProduct::find($id);

        if (empty($product)) return fail('订阅失败');

        if ($this->user->coin < $product->price) return fail('余额不足');

        MiniSubscribe::create([
            'product_id' => $id,
            'user_id' => $this->user->id,
            'type' => $type,
            'price' => $product->price
        ]);

        MiniCoinLog::create([
            'user_id' => $this->user->id,
            'type' => MiniCoinLog::DECREASE,
            'before' => $this->user->coin,
            'value' => $product->price * -1,
            'after' => $this->user->coin - $product->price,
            'remark' => '订阅' . $product->name
        ]);

        $this->user->coin = $this->user->coin - $product->price;
        $this->user->save();

        return success();
    }

    /**
     * 取消订阅
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $type = $request->input('type');

        if (!$this->user) return fail('请重新打开小程序');

        if (!MiniSubscribe::where([
            'product_id' => $id,
            'user_id' => $this->user->id,
            'type' => $type
        ])->exists()
        ) return fail('您没并有订阅该提醒');

        MiniSubscribe::where([
            'product_id' => $id,
            'user_id' => $this->user->id,
            'type' => $type
        ])->delete();

        return success();
    }
}
