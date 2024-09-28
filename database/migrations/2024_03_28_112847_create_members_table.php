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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->default(0)->comment('上级会员ID');
            $table->unsignedInteger('store_id')->comment('商家ID');
            $table->string('name')->default('未定义会员名')->comment('姓名');
            $table->string('avatar')->nullable()->comment('头像');
            $table->enum('gender', [0, 1, 2])->default('0')->comment('性别 0:未知 1:男 2:女');
            $table->string('number')->nullable()->comment('会员卡号');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->date('birthday')->nullable()->comment('生日');
            $table->integer('level_id')->default(0)->comment('会员等级ID');
            $table->decimal('balance', 12)->default(0.00)->comment('余额');
            $table->decimal('integral', 12)->default(0.00)->comment('积分');
            $table->decimal('total_consumption_amount', 12)->default(0.00)->comment('总消费金额');
            $table->integer('total_consumption_times')->default(0)->unsigned()->comment('总消费次数');
            $table->timestamp('first_consumption_at')->nullable()->comment('首次消费时间');
            $table->timestamp('last_consumption_at')->nullable()->comment('最后消费时间');
            $table->string('openid', 28)->nullable();
            $table->string('unionid', 28)->nullable();
            $table->string('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
