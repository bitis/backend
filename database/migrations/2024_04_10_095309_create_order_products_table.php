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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->comment('类型 1 次卡 2 时长卡 3 储值卡 4 项目 5 商品');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('product_sku_id')->nullable()->comment('商品SKU ID');
            $table->string('product_name')->nullable()->comment('商品名称');
            $table->string('product_image')->nullable()->comment('商品图片');
            $table->unsignedInteger('number')->comment('数量');
            $table->decimal('price', 12)->comment('单价');
            $table->decimal('total_amount', 12)->comment('总价');
            $table->decimal('deduct_amount', 12)->comment('抵扣总价');
            $table->string('deduct_desc')->comment('抵扣描述');
            $table->string('use_card_id')->comment('使用卡片ID');
            $table->decimal('real_amount', 12)->comment('实际总价');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
