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
        Schema::create('payment_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        \App\Models\PaymentChannel::insert([
            ['name' => '微信', 'icon' => 'wechat', 'status' => 1],
            ['name' => '支付宝', 'icon' => 'alipay', 'status' => 1],
            ['name' => '现金', 'icon' => 'cash', 'status' => 1],
            ['name' => '其他', 'icon' => 'other', 'status' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_channels');
    }
};
