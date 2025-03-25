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
            $table->integer('store_id')->comment('门店ID');
            $table->integer('category_id')->default(0)->comment('分类ID');
            $table->integer('type')->comment('1 服务 2 实物');
            $table->string('name')->comment('商品名称');
            $table->string('unit', 20)->comment('商品单位');
            $table->string('subtitle')->nullable()->comment('副标题');
            $table->string('images', 500)->nullable()->comment('图片');
            $table->string('content')->nullable()->comment('详情文件地址');
            $table->string('bar_code')->nullable()->comment('条码');
            $table->decimal('price', 12)->default(0.00)->comment('价格');
            $table->decimal('original_price', 12)->nullable()->comment('原价');
            $table->decimal('member_price', 12)->nullable()->comment('会员价');
            $table->integer('stock')->nullable()->default(0)->comment('总库存');
            $table->integer('stock_warn')->nullable()->default(0)->comment('库存预警数量');
            $table->unsignedInteger('sales_count')->default(0)->comment('销量');
            $table->boolean('online')->default(false)->comment('是否上架');
            $table->boolean('multi_spec')->default(false)->comment('规格类型');
            $table->boolean('commission_config')->default(false)->comment('是否开启提成');
            $table->boolean('appointment')->default(1)->comment('可以预约');
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
