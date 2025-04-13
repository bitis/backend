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
        Schema::create('mini_subscribes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('1: Visa 2: Lenovo');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('price');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mini_coin_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('type')->comment('1: 增加 2: 减少');
            $table->string('remark')->comment('备注');
            $table->integer('before');
            $table->integer('value');
            $table->integer('after');
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_subscribes');
    }
};
