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
        Schema::create('appointment_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->char('earliest', 5)->comment('最早预约时间 H:i');
            $table->char('latest', 5)->comment('最晚预约时间 H:i');
            $table->smallInteger('interval')->comment('时间间隔 分钟');
            $table->smallInteger('max_number')->comment('预约人数限制');
            $table->smallInteger('before_time')->comment('预约时间限制 提前时间 分钟');
            $table->tinyInteger('status')->default(1)->comment('状态 1 启用 2 禁用');
            $table->json('slots')->comment('预约时间段');
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('member_id');
            $table->string('member_name')->comment('会员名');
            $table->string('mobile', 15)->comment('预约手机号');
            $table->unsignedInteger('product_id')->comment('预约的项目ID');
            $table->string('product_name')->comment('预约的项目名称');
            $table->timestamp('time')->comment('预约时间');
            $table->char('time_text', 12)->comment('预约时间');
            $table->unsignedInteger('number')->comment('预约人数');
            $table->string('remark')->nullable()->comment('备注');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_configs');
        Schema::dropIfExists('appointments');
    }
};
