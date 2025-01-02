<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->comment('会员ID');
            $table->integer('store_id')->comment('门店ID');
            $table->char('order_number', 22)->comment('订单号');
            $table->integer('type')->comment('类型 1 开卡 2 消费');
            $table->string('intro')->comment('描述');
            $table->decimal('total_amount', 12)->comment('金额');
            $table->decimal('deduct_amount', 12)->comment('抵扣金额');
            $table->decimal('pay_amount', 12)->comment('实际支付金额');
            $table->tinyInteger('payment_id' )->comment('支付方式');
            $table->integer('operator_id')->nullable()->comment('操作人ID');
            $table->boolean('refund')->default(0)->comment('是否退款 0 未退款 1 退款');
            $table->timestamp('refund_at')->nullable()->comment('退款时间');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
