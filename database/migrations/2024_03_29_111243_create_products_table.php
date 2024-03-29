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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->default(0)->comment('分类ID');
            $table->integer('type')->comment('0 服务 1 实物');
            $table->string('name')->comment('商品名称');
            $table->string('subtitle')->nullable()->comment('副标题');
            $table->string('bar_code')->nullable()->comment('条码');
            $table->decimal('price', 12)->default(0.00)->comment('价格');
            $table->decimal('original_price', 12)->default(0.00)->comment('原价');
            $table->decimal('member_price', 12)->default(0.00)->comment('会员价');
            $table->integer('stock')->default(0)->comment('库存');
            $table->integer('stock_warn')->default(0)->comment('库存预警数量');
            $table->boolean('flag')->default(false)->comment('是否上架');
            $table->boolean('spec')->default(false)->comment('是否有规格');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
