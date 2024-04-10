<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\MemberCard;
use App\Models\MemberCardProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStaff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $cards = MemberCard::where('member_id', $request->input('member_id'))
            ->where('status', $request->input('status'))
            ->simplePaginate(getPerPage());

        return success($cards);
    }

    public function open(Request $request): JsonResponse
    {
        $cardId = $request->input('card_id');
        $memberId = $request->input('member_id');
        $total_amount = $request->input('total_amount');
        $pay_amount = $request->input('pay_amount');
        $payment_type = $request->input('payment_type');
        $order_number = Order::generateNumber($this->store_id);
        $remark = $request->input('remark');

        $staffs = $request->input('staffs');

        $deduct_amount = $total_amount - $pay_amount;

        $card = Card::where('store_id', $this->store_id)->find($cardId);

        if (empty($card)) return fail('会员卡不存在');

        /**
         * 创建订单
         */
        $order = Order::create([
            'member_id' => $memberId,
            'store_id' => $this->store_id,
            'order_number' => $order_number,
            'type' => Order::TYPE_OPEN,
            'intro' => '开卡',
            'total_amount' => $total_amount,
            'deduct_amount' => $deduct_amount,
            'pay_amount' => $pay_amount,
            'payment_type' => $payment_type,
            'operator_id' => $this->operator_id,
        ]);

        $product = OrderProduct::create([
            'order_id' => $order->id,
            'type' => $card->type,
            'product_id' => $card->id,
            'product_name' => $card->name,
            'product_sku_id' => 0,
            'product_image' => '',
            'number' => 1,
            'price' => $card->price,
            'total_amount' => $total_amount,
            'deduct_amount' => $deduct_amount,
            'deduct_desc' => '手动调整金额',
            'use_card_id' => 0,
            'real_amount' => $pay_amount,
        ]);

        foreach ($staffs as $staff) {
            OrderStaff::create([
                'order_id' => $order->id,
                'staff_id' => $staff['id'],
                'product_id' => $card->id,
                'product_name' => $card->name,
                'number' => 1,
                'product_type' => $card->type,
                'intro' => '开卡' . $card->name . '×1',
                'performance' => $staff['performance'],
                'commission' => $staff['commission'],
            ]);
        }

        /**
         * 开卡
         */
        $valid_time = $card->valid_type == Card::VALID_FOREVER ? null : now()->addDays($card->valid_days);

        $memberCard = MemberCard::create([
            'member_id' => $memberId,
            'store_id' => $this->store_id,
            'type' => $card->type,
            'status' => MemberCard::STATUS_ENABLE,
            'card_id' => $card->id,
            'price' => $pay_amount,
            'valid_type' => $card->valid_type,
            'valid_time' => $valid_time,
            'remark' => $remark,
            'commission_config' => $card->commission_config,
        ]);

        foreach ($card->products as $product) {
            MemberCardProduct::create([
                'member_card_id' => $memberCard->id,
                'product_id' => $product->id,
                'store_id' => $this->store_id,
                'number_type' => $product->number_type,
                'origin_number' => $product->number,
                'used_number' => 0,
                'current_number' => $product->number,
                'valid_time' => $valid_time,
                'status' => MemberCardProduct::STATUS_ENABLE
            ]);

            CardTransaction::create([
                'member_id' => $memberId,
                'store_id' => $this->store_id,
                'member_card_id' => $memberCard->id,
                'type' => CardTransaction::TYPE_OPEN,
                'product_id' => $product->id,
                'value' => $product->number,
                'after' => $product->number,
                'order_id' => $order->id,
                'refund' => 0,
                'operator_id' => $this->operator_id,
                'remark',
            ]);
        }

        return success();
    }
}
