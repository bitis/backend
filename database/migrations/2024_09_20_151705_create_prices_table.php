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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10)->default(0)->comment('价格');
            $table->decimal('original_price', 10)->default(0)->comment('原价');
            $table->boolean('forever')->default(0)->comment('永久会员');
            $table->integer('month')->default(0)->comment('开通时长');
            $table->string('name')->nullable()->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->boolean('recommend')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->comment('订单号');
            $table->integer('store_id')->comment('门店ID');
            $table->decimal('price', 10)->default(0)->comment('价格');
            $table->decimal('original_price', 10)->default(0)->comment('原价');
            $table->boolean('forever')->default(0)->comment('永久会员');
            $table->integer('month')->default(0)->comment('开通时长');
            $table->string('name')->nullable()->comment('名称');
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            $table->string('payment_no')->nullable()->comment('支付单号');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->boolean('handled')->default(0)->comment('已处理');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
        Schema::dropIfExists('store_orders');
    }
};
