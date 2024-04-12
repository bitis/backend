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
        Schema::create('commission_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('store_id');
            $table->tinyInteger('configurable_type')->comment('配置类型 1:商品 2:快速消费 3:办卡 4:储值卡');
            $table->integer('configurable_id')->comment('配置对象id')->default(0);
            $table->boolean('share_out')->comment('均摊提成 1:均摊 0:不均摊')->default(0);
            $table->tinyInteger('type')->comment('计算方式 1:固定金额 2:百分比')->default(1);
            $table->boolean('deduct_cost')->comment('是否扣除成本后计算 1:扣除 0:不扣除')->default(0);
            $table->decimal('rate', 4, 2)->comment('百分比')->default(0);
            $table->decimal('fixed_amount', 10, 2)->comment('固定金额')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_configs');
    }
};
