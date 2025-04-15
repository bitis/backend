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
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DealController extends Controller
{
    /**
     * 开卡
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function applyCard(Request $request): JsonResponse
    {
        $cardId = $request->input('card_id');
        $memberId = $request->input('member_id');
        $pay_amount = $request->input('pay_amount');
        $payment_id = $request->input('payment_id');
        $order_number = Order::generateNumber($this->store_id);
        $remark = $request->input('remark');

        $staffs = $request->input('staffs');

        try {
            DB::beginTransaction();
            $card = Card::where('store_id', $this->store_id)->find($cardId);

            throw_if(empty($card), new Exception('会员卡不存在'));

            $member = Member::where('store_id', $this->store_id)->find($memberId);

            throw_if(empty($member), new Exception('会员不存在'));

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
                'payment_id' => $payment_id,
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
                'price' => $pay_amount,
                'total_price' => $total_amount,
                'original_price' => $card->price,
                'total_original_price' => $total_amount,
                'deduct_price' => $deduct_amount,
                'total_deduct_price' => $deduct_amount,
                'deduct_desc' => '开卡',
                'use_card_id' => 0,
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
                    'name' => $card->name,
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
        } catch (Exception|Throwable $exception) {
            DB::rollBack();
            if (app()->environment() !== 'production') throw $exception;
            return fail($exception->getMessage());
        }

        return success();
    }

    /**
     * 普通消费
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function normal(Request $request): JsonResponse
    {
        $member = $request->input('member');
        $products = $request->input('products');
        $payment = $request->input('payment');

        if (!empty($member['id'])) {
            $member = Member::where('store_id', $this->store_id)
                ->where('id', $member['id'])
                ->select(Member::simpleFields)
                ->first();
        } elseif ($member['mobile']) {
            $member = Member::select(Member::simpleFields)
                ->firstOrCreate([
                    'mobile' => $member['mobile'],
                    'store_id' => $this->store_id
                ], [
                    'name' => $member['name']
                ]);
        }

        if (empty($products)) return fail('请选择要消费的商品');

        $total_price = 0;
        $total_original_price = 0;
        $total_deduct_price = 0;

        foreach ($products as &$product) {
            $product['total_price'] = $product['price'] * $product['number'];
            $product['total_original_price'] = $product['original_price'] * $product['number'];
            $product['deduct_price'] = $product['original_price'] - $product['price'];
            $product['total_deduct_price'] = $product['deduct_price'] * $product['number'];

            $total_price += $product['total_price'];
            $total_original_price += $product['total_original_price'];
            $total_deduct_price += $product['total_deduct_price'];

            if (isset($product['staffs'])) {
                foreach ($product['staffs'] as &$staff) {
                    $staff['performance'] = $product['total_original_price'];
                    $staff['commission'] = 0;
                }
            }
        }

        if ($request->input('submit')) {
            $order = Order::create([
                'member_id' => $member['id'],
                'store_id' => $this->store_id,
                'order_number' => Order::generateNumber($this->store_id),
                'type' => Order::TYPE_NORMAL,
                'intro' => '普通消费',
                'total_amount' => $total_original_price,
                'deduct_amount' => $total_deduct_price,
                'pay_amount' => $total_price,
                'payment_id' => $payment ? $payment['id'] : null,
                'operator_id' => $this->operator_id,
                'remark' => $request->input('remark'),
            ]);

            foreach ($products as $mProduct) {
                $_product = Product::where('store_id', $this->store_id)->find($mProduct['product_id']);

                $_order_product = OrderProduct::create([
                    'product_image',
                    'deduct_desc',
                    'type' => $_product->type,
                    'order_id' => $order->id,
                    'product_id' => $_product->id,
                    'product_name' => $_product->name,
                    'number' => $mProduct['number'],
                    'price' => $mProduct['price'],
                    'total_price' => $mProduct['total_price'],
                    'original_price' => $_product->price,
                    'total_original_price' => $mProduct['total_original_price'],
                    'deduct_price' => $mProduct['deduct_price'],
                    'total_deduct_price' => $mProduct['total_deduct_price'],
                    'level_deduct' => isset($mProduct['level_deduct']) ? $mProduct['level_deduct'] * $mProduct['number'] : 0,
                ]);

                if ($mProduct['staffs']) OrderStaff::write($mProduct['staffs'], $_order_product);
            }
        }

        return success([
            'member' => $member,
            'products' => $products,
            'total_price' => $total_price,
            'total_original_price' => $total_original_price,
            'total_deduct_price' => $total_deduct_price
        ]);
    }
}
