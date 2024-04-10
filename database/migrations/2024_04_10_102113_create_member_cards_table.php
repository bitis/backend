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
        Schema::create('member_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->comment('会员ID');
            $table->integer('store_id')->comment('门店ID');
            $table->integer('type')->comment('类型 1 次卡 2 时长卡 3 储值卡');
            $table->integer('card_id')->comment('会员卡ID');
            $table->decimal('price', 12)->comment('售价');
            $table->integer('valid_type')->comment('1 永久 2 限时');
            $table->timestamp('valid_time')->nullable()->comment('截止日期');
            $table->string('remark')->nullable();
            $table->boolean('commission_config')->default(false)->comment('是否开启提成');
            $table->boolean('status')->default(1)->comment('1 有效 2 无效');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_cards');
    }
};
