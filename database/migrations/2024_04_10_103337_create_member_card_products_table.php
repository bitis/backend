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
        Schema::create('member_card_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('member_id')->comment('会员ID');
            $table->unsignedInteger('member_card_id')->comment('会员卡ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('store_id')->comment('门店ID');
            $table->tinyInteger('number_type')->default(1)->comment('1 限次 2 不限次');
            $table->unsignedInteger('origin_number')->comment('初始数量');
            $table->unsignedInteger('used_number')->default(0)->comment('已使用数量');
            $table->unsignedInteger('current_number')->comment('数量');
            $table->timestamp('valid_time')->nullable()->comment('截止日期');
            $table->tinyInteger('status')->default(1)->comment('1 有效 2 禁用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_card_products');
    }
};
