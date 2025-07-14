<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\InsufficientException;
use App\Exceptions\MemberNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\MemberCard;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStaff;
use App\Models\Product;
use App\Models\StoreStat;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumeController extends Controller
{
    /**
     * 只输金额的消费
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fast(Request $request): JsonResponse
    {
        $memberId = $request->input('member_id');
        $pay_amount = $request->input('pay_amount');
        $real_amount = $request->input('real_amount');
        $total_amount = $request->input('total_amount');
        $payment_type = $request->input('payment_type');
        $deduct_amount = $request->input('deduct_amount');
        $staffs = $request->input('staffs');

        $deductions = $request->input('deductions');

        try {
            DB::beginTransaction();
            $order = Order::create([
                'member_id' => $memberId,
                'store_id' => $this->store_id,
                'order_number' => Order::generateNumber($this->store_id),
                'type' => Order::TYPE_FAST,
                'intro' => '快速消费',
                'total_amount' => $total_amount,
                'deduct_amount' => $deduct_amount,
                'pay_amount' => $pay_amount,
                'payment_type' => $payment_type,
                'operator_id' => $this->operator_id,
                'remark' => $request->input('remark'),
            ]);

            OrderProduct::create([
                'type' => OrderProduct::TYPE_FAST_CONSUME,
                'order_id' => $order->id,
                'product_id' => 0,
                'product_name' => '快捷收款',
                'number' => 1,
                'price' => $total_amount,
                'total_price' => $total_amount,
                'deduct_amount' => $deduct_amount,
                'deduct_desc' => '手动改价 ' . $deduct_amount,
                'real_amount' => $pay_amount,
            ]);

            foreach ($staffs as $staff) {
                OrderStaff::create([
                    'order_id' => $order->id,
                    'staff_id' => $staff['id'],
                    'product_id' => 0,
                    'product_name' => '快速消费 ' . $pay_amount,
                    'number' => 1,
                    'product_type' => OrderStaff::TYPE_NOT_RECORD,
                    'intro' => '快速消费 ' . $pay_amount,
                    'performance' => $staff['performance'],
                    'commission' => $staff['commission'],
                ]);
            }

            if ($memberId && $deductions)
                Order::deduction($deductions, $memberId, $this->store_id, $order->id, $this->operator_id);

            StoreStat::updateOrCreate([
                'store_id' => $this->store_id,
                'date' => date('Y-m-d')
            ])->update([
                'consumer_member' => DB::raw("consumer_member + 1"),
                'new_users' => DB::raw("new_order + 1"),
                'use_money_amount' => DB::raw("use_money_amount + " . $pay_amount),
                'month' => date('Ym'),
                'year' => date('Y')
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return fail($exception->getMessage());
        }

        return success($order);
    }

    /**
     * 普通消费
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function normal(Request $request): JsonResponse
    {
        $memberId = $request->input('member_id');
        $products = $request->input('products');
        $deductions = $request->input('deductions');

        try {
            DB::beginTransaction();
            $order = Order::create([
                'member_id' => $memberId,
                'store_id' => $this->store_id,
                'order_number' => Order::generateNumber($this->store_id),
                'type' => Order::TYPE_NORMAL,
                'intro' => '普通消费',
                'total_amount' => $request->input('total_amount'),
                'deduct_amount' => $request->input('deduct_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'payment_type' => $request->input('payment_type'),
                'operator_id' => $this->operator_id,
                'remark' => $request->input('remark'),
            ]);

            foreach ($products as $product) {
                $_product = Product::where('store_id', $this->store_id)->find($product['id']);
                $_card = $product['card'];

                $_order_product = OrderProduct::create([
                    'type' => $_product->type,
                    'order_id' => $order->id,
                    'product_id' => $_product->id,
                    'product_name' => $_product->name,
                    'number' => $product['number'],
                    'price' => $product['price'],
                    'original_price' => $_product->price,
                    'real_amount' => $product['real_amount'], // 售价总计
                    'total_price' => $_product->price * $product['number'], // 原价总计
                    'deduct_amount' => isset($product['deduct_amount']) ? $product['deduct_amount'] * $product['number'] : 0,
                    'level_deduct' => isset($product['level_deduct']) ? $product['level_deduct'] * $product['number'] : 0,
                    'times_card_deduct' => ($_card && $_card['type'] == Card::TYPE_TIMES) ? $product['real_amount'] : 0,
                    'duration_card_deduct' => ($_card && $_card['type'] == Card::TYPE_DURATION) ? $product['real_amount'] : 0,
                    'deduct_desc' => '手动改价',
                ]);

                if ($product['staffs']) OrderStaff::write($product['staffs'], $_order_product);

                if ($product['card']) {
                    MemberCard::consume($memberId, $product['card']['id'], $product['id'], $product['number'], $order->id, $this->operator_id);
                }

                if ($memberId && $deductions) Order::deduction($deductions, $memberId, $this->store_id, $order->id, $this->operator_id);
            }

            StoreStat::updateOrCreate([
                'store_id' => $this->store_id,
                'date' => date('Y-m-d')
            ])->update([
                'consumer_member' => DB::raw("consumer_member + 1"),
                'new_users' => DB::raw("new_order + 1"),
                'month' => date('Ym'),
                'year' => date('Y')
            ]);
            DB::commit();
        } catch (MemberNotFoundException|InsufficientException $exception) {
            DB::rollBack();
            return fail($exception->getMessage());
        }

        return success();
    }
}
