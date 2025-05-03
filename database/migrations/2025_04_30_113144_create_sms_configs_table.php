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
        Schema::create('sms_configs', function (Blueprint $table) {
            $table->id();
            $table->string('store_id');
            $table->boolean('is_new')->default(1)->comment('是否是新用户');
            $table->integer('balance')->default(0)->comment('短信数量');
            $table->boolean('consume_switch')->default(false)->comment('是否开启短信消费');
            $table->timestamps();
        });

        Schema::create('sms_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('套餐名称');
            $table->integer('package_id')->comment('套餐ID套餐ID');
            $table->string('description')->comment('套餐介绍');
            $table->integer('number')->comment('短信数量');
            $table->decimal('price')->comment('价格');
            $table->decimal('unit_price', 8, 3)->comment('单价');
            $table->decimal('original_price')->comment('原价');
            $table->integer('limit')->default(0)->comment('限制购买次数');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sms_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('name')->comment('套餐名称');
            $table->string('order_no')->comment('订单号');
            $table->integer('number')->comment('短信数量');
            $table->decimal('price')->comment('价格');
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->timestamp('refunded_at')->nullable()->comment('退款时间');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('remark')->comment('备注');
            $table->tinyInteger('type')->comment('类型 1:消费通知 2:订单变动');
            $table->string('order_id')->nullable()->comment('订单ID');
            $table->string('record_id')->nullable()->comment('发送记录ID');
            $table->integer('number')->comment('变动数量');
            $table->integer('balance')->comment('剩余数量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_configs');
    }
};
