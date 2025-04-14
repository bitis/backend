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
        Schema::create('visa_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('entranceImg')->nullable();
            $table->string('seckillImg')->nullable();
            $table->decimal('sellPrice')->nullable();
            $table->decimal('purchasePrice')->nullable();
            $table->integer('stockStatus')->nullable();
            $table->string('v_id')->nullable();
            $table->string('activityId')->nullable();
            $table->string('channelId')->nullable();
            $table->integer('stock')->nullable();
            $table->text('goodsIntroduction')->nullable();
            $table->text('purchaseNotes')->nullable();
            $table->string('goodsTagOne')->nullable();
            $table->string('goodsTagTwo')->nullable();
            $table->integer('price')->default(5)->comment('价格');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_products');
    }
};
