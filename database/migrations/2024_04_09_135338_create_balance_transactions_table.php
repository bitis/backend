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
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->comment('会员ID');
            $table->integer('type')->comment('类型 1 充值 2 支付 3 退款 4 手动增加 5 手动减少');
            $table->integer('store_id')->comment('门店ID');
            $table->decimal('amount', 12)->comment('金额');
            $table->decimal('after', 12)->comment('剩余金额');
            $table->integer('order_id')->comment('订单ID');
            $table->boolean('refund')->default(0)->comment('是否退款 0 未退款 1 退款');
            $table->string('remark')->nullable();
            $table->integer('operator_id')->nullable()->comment('操作人ID');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
