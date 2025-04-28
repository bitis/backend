<?php

namespace App\Models;

use App\Exceptions\InsufficientException;
use App\Exceptions\MemberNotFoundException;
use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'member_id',
        'store_id',
        'order_number',
        'type',
        'intro',
        'total_amount',
        'deduct_amount',
        'real_amount',
        'pay_amount',
        'payment_id',
        'operator_id',
        'refund',
        'refund_at',
        'remark'
    ];

    const TYPE_OPEN = 1; // 开卡
    const TYPE_FAST = 2; // 快速消费
    const TYPE_NORMAL = 3; // 普通消费
    const TYPE_CARD = 3; // 普通消费
    const TYPE_FAST_STORED = 3; // 快速充值
    const TYPE_FAST_TIMES = 4; // 快速充次

    /**
     * 生成22位订单号
     *
     * @param int $store_id
     * @return string
     */
    public static function generateNumber(int $store_id): string
    {
        $date = date('ymdHis');
        return sprintf("%s%06d%s", $date, $store_id, rand(1000, 9999));
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(OrderStaff::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentChannel::class, 'payment_id', 'id');
    }

    /**
     * @param $deductions
     * @param $memberId
     * @param $storeId
     * @param $orderId
     * @param $operatorId
     * @return void
     * @throws InsufficientException
     * @throws MemberNotFoundException
     */
    public static function deduction($deductions, $memberId, $storeId, $orderId, $operatorId)
    {
        $member = Member::where('store_id', $storeId)->find($memberId);
        if (empty($member)) throw new MemberNotFoundException('会员不存在');

        foreach ($deductions as $deduction) {
            if ($deduction['type'] == 1) {
                if ($member->balance < $deduction['amount'])
                    throw new InsufficientException('余额不足');

                $member->balance -= $deduction['amount'];

                $member->save();

                BalanceTransaction::create([
                    'store_id' => $storeId,
                    'type' => BalanceTransaction::TYPE_PAY,
                    'member_id' => $memberId,
                    'amount' => $deduction['amount'],
                    'after' => $member->balance,
                    'order_id' => $orderId,
                    'operator_id' => $operatorId,
                    'remark' => '快速消费 - ' . $deduction['amount'],
                ]);
            }
        }
    }
}
