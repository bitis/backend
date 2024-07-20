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
        Schema::create('sms_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->string('title')->comment('任务标题');
            $table->string('signature')->comment('短信签名');
            $table->string('content')->comment('短信内容');
            $table->string('file')->nullable();
            $table->unsignedInteger('content_length')->comment('短信内容长度');
            $table->unsignedInteger('mobile_count')->comment('手机号数量');
            $table->unsignedInteger('failed_count')->nullable()->comment('发送失败数量');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0 待发送 1 发送中 2 ');
            $table->timestamps();
        });

        Schema::create('sms_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('sms_record_id');
            $table->string('mobile')->comment('手机号');
            $table->unsignedTinyInteger('source')->comment('来源 1 输入 2 上传 3 会员');
            $table->string('content')->comment('短信内容');
            $table->string('result')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0 待发送 1 发送中 2 发送成功 3 发送失败');
            $table->timestamp('send_at')->nullable()->comment('发送时间');
            $table->timestamp('response_at')->nullable()->comment('发送结果时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_records');
        Schema::dropIfExists('sms_details');
    }
};
