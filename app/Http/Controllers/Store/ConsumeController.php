<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStaff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsumeController extends Controller
{
    public function fast(Request $request): JsonResponse
    {
        $memberId = $request->input('member_id');
        $pay_amount = $request->input('pay_amount');
        $total_amount = $request->input('total_amount');
        $payment_type = $request->input('payment_type');
        $deduct_amount = $request->input('deduct_amount');
        $staffs = $request->input('staffs');

        $use_member_card = $request->input('use_member_card');

        $order = Order::create([
            'member_id' => $memberId,
            'store_id' => $this->store_id,
            'order_number' => Order::generateNumber($this->store_id),
            'type' => Order::TYPE_CONSUME_FAST,
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
            'total_amount' => $total_amount,
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

        if ($memberId && $use_member_card) {
            $member = Member::where('store_id', $this->store_id)->find($memberId);
            if (empty($member)) return fail('会员不存在');

            if ($member->balance < $deduct_amount) return fail('会员卡余额不足');

            $member->balance -= $pay_amount;

            $member->save();

            BalanceTransaction::create([
                'store_id' => $this->store_id,
                'type' => BalanceTransaction::TYPE_PAY,
                'member_id' => $memberId,
                'amount' => $pay_amount,
                'after' => $member->balance,
                'order_id' => $order->id,
                'operator_id' => $this->operator_id,
                'remark' => '快速消费 - ' . $pay_amount,
            ]);
        }

        return success($order);
    }
}
