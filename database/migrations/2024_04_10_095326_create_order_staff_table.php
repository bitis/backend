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
        Schema::create('order_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('staff_id')->comment('员工ID');
            $table->unsignedInteger('product_id')->comment('订单商品ID');
            $table->string('product_name')->comment('订单商品名称');
            $table->unsignedInteger('product_type')->comment('订单商品类型 1 次卡 2 时长卡 3 储值卡 4 项目 5 商品 6 未记录商品');
            $table->unsignedInteger('number')->comment('购买数量');
            $table->string('intro')->comment('商品描述');
            $table->decimal('performance', 12)->comment('业绩');
            $table->decimal('commission', 12)->comment('提成');
            $table->string('remark')->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_staff');
    }
};
