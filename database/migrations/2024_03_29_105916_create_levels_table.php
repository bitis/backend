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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('等级名称');
            $table->boolean('flag')->default(false)->comment('是否启用折扣');
            $table->decimal('discount', 2,1)->comment('折扣 ');
            $table->boolean('item_limit')->default(false)->comment('折扣限制 0 不限制 1 限制');
            $table->integer('item_count')->default(0)->comment('折扣项目数量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
