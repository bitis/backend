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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->decimal('price', 12)->default(0.00)->comment('价格');
            $table->decimal('original_price', 12)->default(0.00)->comment('原价 划线价');
            $table->decimal('member_price', 12)->default(0.00)->comment('会员价');
            $table->integer('stock')->default(0)->comment('库存');
            $table->string('bar_code')->nullable()->comment('条码');
            $table->integer('duration')->nullable()->default(0)->comment('服务时长');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
