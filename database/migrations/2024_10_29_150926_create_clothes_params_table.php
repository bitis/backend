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
        Schema::create('clothes_params', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->tinyInteger('type')->comment('参数类型');
            $table->string('name')->comment('参数名称');
            $table->string('code')->nullable()->comment('快捷搜索');
            $table->string('image')->nullable()->comment('图片');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothes_params');
    }
};
