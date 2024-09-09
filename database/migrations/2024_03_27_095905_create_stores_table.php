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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->integer('industry_id')->nullable()->comment('行业ID');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_name');
            $table->string('contact_mobile');
            $table->string('contact_wechat')->nullable();
            $table->string('official_account_qrcode')->nullable()->comment('微信公众号二维码');
            $table->timestamp('expiration_date')->comment('到期时间');
            $table->string('images')->nullable()->comment('门店图片');
            $table->string('introduction')->nullable()->comment('门店简介');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
