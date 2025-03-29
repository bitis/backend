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
        Schema::create('export_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->string('order_no', 15)->comment('订单号');
            $table->decimal('price')->default(0)->comment('价格');
            $table->decimal('original_price')->default(0)->comment('原价');
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            $table->string('payment_no')->nullable()->comment('支付单号');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->timestamp('export_at')->nullable()->comment('导出时间');
            $table->tinyInteger('status')->default(0)->comment('支付状态 0 未支付 1 已支付 2 已取消 3 已退款 4 已导出');
            $table->string('file')->nullable()->comment('导出文件');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_orders');
    }
};
