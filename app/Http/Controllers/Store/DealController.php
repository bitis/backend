<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberCardProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStaff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealController extends Controller
{
    /**
     * 开卡
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function applyCard(Request $request): JsonResponse
    {
        $cardId = $request->input('card_id');
        $memberId = $request->input('member_id');
        $pay_amount = $request->input('pay_amount');
        $payment_type = $request->input('payment_type');
        $order_number = Order::generateNumber($this->store_id);
        $remark = $request->input('remark');

        $staffs = $request->input('staffs');

        try {
            DB::beginTransaction();
            $card = Card::where('store_id', $this->store_id)->find($cardId);

            throw_if(empty($card), new \Exception('会员卡不存在'));

            $member = Member::where('store_id', $this->store_id)->find($memberId);

            throw_if(empty($member), new \Exception('会员不存在'));

            $total_amount = $card->price;
            $deduct_amount = $total_amount - $pay_amount;

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
                'real_amount' => $pay_amount,
                'payment_type' => $payment_type,
                'operator_id' => $this->operator_id,
            ]);

            OrderProduct::create([
                'order_id' => $order->id,
                'type' => $card->type,
                'product_id' => $card->id,
                'product_name' => $card->name,
                'product_sku_id' => 0,
                'product_image' => '/static/member/card.png',
                'number' => 1,
                'price' => $card->price,
                'total_amount' => $total_amount,
                'deduct_amount' => $deduct_amount,
                'deduct_desc' => '开卡',
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
            if ($card->type == Card::TYPE_RECHARGE) {
                // 储值卡
                $stored_money = $card->price + $card->bonus;
                $member->balance += $stored_money;

                BalanceTransaction::create([
                    'member_id' => $memberId,
                    'type' => BalanceTransaction::TYPE_RECHARGE,
                    'store_id' => $this->store_id,
                    'amount' => $stored_money,
                    'after' => $member->balance,
                    'order_id' => $order->id,
                    'refund' => 0,
                    'remark' => '开卡',
                    'operator_id' => $this->operator_id,
                ]);

                $member->save();
            } else {
                // 次卡 时长卡
                $valid_time = $card->valid_type == Card::VALID_FOREVER ? null : now()->addDays($card->valid_days);

                $memberCard = MemberCard::create([
                    'member_id' => $memberId,
                    'store_id' => $this->store_id,
                    'type' => $card->type,
                    'status' => MemberCard::STATUS_ENABLE,
                    'card_id' => $card->id,
                    'card_name' => $card->name,
                    'price' => $pay_amount,
                    'valid_type' => $card->valid_type,
                    'valid_time' => $valid_time,
                    'remark' => $remark,
                    'commission_config' => $card->commission_config,
                ]);

                foreach ($card->products as $product) {
                    MemberCardProduct::create([
                        'member_id' => $memberId,
                        'member_card_id' => $memberCard->id,
                        'product_id' => $product->product_id,
                        'type' => $product->type,
                        'store_id' => $this->store_id,
                        'number_type' => $card->type == Card::TYPE_TIMES ? MemberCardProduct::NUMBER_TYPE_UNLIMITED : MemberCardProduct::NUMBER_TYPE_LIMIT,
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
            }
            DB::commit();
        } catch (\Exception|\Throwable $exception) {
            DB::rollBack();
            if (app()->environment() !== 'production') throw $exception;
            return fail($exception->getMessage());
        }

        return success();
    }

    /**
     * 预览消费订单
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function preview(Request $request): JsonResponse
    {
        $member = $request->input('member');
        $products = $request->input('products');

        if (empty($member['id'])) {
            $member = Member::where('store_id', $this->store_id)
                ->where('id', $member['id'])
                ->select(Member::simpleFields)
                ->first();
        } elseif ($member['mobile']) {
            $member = Member::where('store_id', $this->store_id)
                ->where('mobile', $member['mobile'])
                ->select(Member::simpleFields)
                ->first();
        }

        if (empty($products)) return fail('请选择要消费的商品');

        $pay_amount = 0;
        $total_amount = 0;
        $deduct_amount = 0;

        foreach ($products as &$product) {
            $product['total_amount'] = $product['original_price'] * $product['number'];
            $product['real_amount'] = $product['price'] * $product['number'];
            $product['deduct_amount'] = $product['total_amount'] - $product['real_amount'];

            $pay_amount += $product['real_amount'];
            $total_amount += $product['total_amount'];
            $deduct_amount += $product['deduct_amount'];
        }

        return success([
            'member' => $member,
            'products' => $products,
            'total_amount' => $total_amount,
            'real_amount' => $pay_amount,
            'deduct_amount' => $deduct_amount
        ]);
    }

    public function consume(Request $request)
    {
        $member = $request->input('member');
        $products = $request->input('products');
        $payment = $request->input('payment');
        if (empty($member->id)) {
            $member = Member::where('store_id', $this->store_id)->where('id', $member->id)->first();
        } elseif ($member->mobile) {
            $_member = Member::where('store_id', $this->store_id)->where('mobile', $member->mobile)->first();

            if ($_member) {
                $member = $_member;
            } else {
                $member = Member::create([
                    'store_id' => $this->store_id,
                    'name' => $member->name,
                    'mobile' => $member->mobile
                ]);
            }
        }

        if (empty($products)) return fail('请选择要消费的商品');

        $total_amount = 0;
        $deduct_amount = 0;
        $real_amount = 0;
        $pay_amount = 0;

        foreach ($products as $product) {
            $total_amount += $product->original_price * $product->number;
            $real_amount += $product->price * $product->number;
        }
        return success([
            'member' => $member,
            'products' => $products,
            'total_amount' => $total_amount,
            'real_amount' => $real_amount
        ]);
    }
}
