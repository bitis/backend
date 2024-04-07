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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('name');
            $table->integer('type')->comment('1 次卡 2 时长卡');
            $table->decimal('price', 12)->comment('售价');
            $table->integer('valid_type')->comment('1 永久 2 限时（购买后多少天）');
            $table->integer('valid_days')->default(0)->comment('有效期：购买后多少天');
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
        Schema::dropIfExists('cards');
    }
};
