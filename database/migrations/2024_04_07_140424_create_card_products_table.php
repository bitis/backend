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
        Schema::create('card_products', function (Blueprint $table) {
            $table->id();
            $table->integer('card_id');
            $table->integer('product_id')->comment('项目id');
            $table->string('name')->comment('项目名称');
            $table->integer('number')->default(1)->comment('数量');
            $table->integer('type')->comment('1 储值项目 2 赠送项目');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_products');
    }
};
