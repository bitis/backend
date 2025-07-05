<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->integer('new_member')->default(0)->comment('新增会员');
            $table->integer('new_order')->default(0)->comment('新增订单');
            $table->integer('consumer_member')->default(0)->comment('消费会员数');
            $table->integer('sale_card_amount')->default(0)->comment('售卡金额');
            $table->integer('use_card_amount')->default(0)->comment('使用储值卡销售金额');
            $table->integer('use_card_times')->default(0)->comment('使用次卡次数');
            $table->integer('use_money_amount')->default(0)->comment('使用现金销售金额');
            $table->integer('sale_amount')->default(0)->comment('总销售金额');
            $table->integer('cost_amount')->default(0)->comment('商品成本金额');
            $table->integer('staff_sale_amount')->default(0)->comment('员工业绩金额');
            $table->integer('staff_bonus_amount')->default(0)->comment('员工提成金额');
            $table->integer('profit_amount')->default(0)->comment('利润');
            $table->date('date')->comment('日期');
            $table->unsignedMediumInteger('month')->comment('月份');
            $table->unsignedMediumInteger('year')->comment('年份');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_stats');
    }
};
