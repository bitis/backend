<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\MemberCard;
use App\Models\MemberCardProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MemberCardController extends Controller
{
    /**
     * 会员卡列表
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $cards = MemberCard::with('products', 'products.product')
            ->where('member_id', $request->input('member_id'))
            ->where('status', $request->input('status', MemberCard::STATUS_ENABLE))
            ->where('valid', 1)
            ->get();

        return success($cards);
    }

    /**
     * 扣卡消费时展示会员有的产品
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function products(Request $request): JsonResponse
    {
        $memberId = $request->input('member_id');

        $memberCardProducts = MemberCardProduct::with('product', 'memberCard')
            ->where('member_id', $memberId)
            ->get()->toArray();

        $products = [];

        foreach ($memberCardProducts as $memberCardProduct) {
            $_product = $memberCardProduct['product'];
            $_card = array_merge(Arr::only($memberCardProduct['member_card'], [
                'id',
                'member_id',
                'store_id',
                'type',
                'card_id',
                'name',
                'price',
                'valid_type',
                'valid_time'
            ]), Arr::only($memberCardProduct, [
                'product_id',
                'number_type',
                'origin_number',
                'used_number',
                'current_number',
                'valid_time',
                'status',
            ]));

            if (!isset($products[$_product['id']]))
                $products[$_product['id']] = $_product;
            $products[$_product['id']]['cards'][] = $_card;
        }

        return success($products);
    }
}
