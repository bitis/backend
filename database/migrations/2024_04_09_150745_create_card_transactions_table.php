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
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->comment('会员ID');
            $table->integer('member_card_id')->comment('会员ID');
            $table->integer('store_id')->comment('门店ID');
            $table->integer('type')->comment('类型 1 办卡 2 消费 3 退款 4 手动增加 5 手动减少 6 修改有效期');
            $table->integer('product_id')->nullable()->comment('商品ID');
            $table->integer('value')->comment('修改数量');
            $table->integer('after')->comment('剩余数量');
            $table->integer('order_id')->comment('订单ID');
            $table->boolean('refund')->default(0)->comment('是否退款 0 未退款 1 退款');
            $table->timestamp('old_valid_time')->nullable()->comment('原有效期');
            $table->timestamp('new_valid_time')->nullable()->comment('新有效期');
            $table->integer('operator_id')->nullable()->comment('操作人ID');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_transactions');
    }
};
