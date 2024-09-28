<?php

namespace App\Models;

use App\Exceptions\InsufficientException;
use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCard extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $fillable = [
        'member_id',
        'store_id',
        'type',
        'status',
        'card_id',
        'card_name',
        'price',
        'valid_type',
        'valid_time',
        'valid',
        'remark',
        'commission_config',
        'status'
    ];

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

    public function products(): HasMany
    {
        return $this->hasMany(MemberCardProduct::class);
    }


    /**
     * 扣卡消费
     *
     * @param mixed $memberId 会员ID
     * @param int $cardId 会员卡ID
     * @param int $productId 商品ID
     * @param int $number 数量
     * @param int $orderId 订单ID
     * @return void
     * @throws InsufficientException
     */
    public static function consume(int $memberId, int $cardId, int $productId, int $number, int $orderId, $operatorId): void
    {
        $_member_card_product = MemberCardProduct::where('member_card_id', $cardId)
            ->where('product_id', $productId)
            ->where('status', MemberCardProduct::STATUS_ENABLE)
            ->first();

        if (!$_member_card_product || $_member_card_product->number < $number) {
            throw new InsufficientException('卡内余额不足');
        }

        $_member_card_product->number -= $number;
        $_member_card_product->save();

        CardTransaction::create([
            'member_id' => $memberId,
            'member_card_id' => $cardId,
            'store_id' => $_member_card_product->store_id,
            'type' => CardTransaction::TYPE_CONSUME,
            'product_id' => $productId,
            'value' => $number,
            'after' => $_member_card_product->number,
            'order_id' => $orderId,
            'refund' => 0,
            'operator_id' => $operatorId,
        ]);
    }
}
