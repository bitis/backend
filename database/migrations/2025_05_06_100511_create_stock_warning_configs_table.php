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
        Schema::create('stock_warning_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->integer('min_number')->default(0)->comment('最小库存');
            $table->boolean('status')->default(0)->comment('是否启用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_warning_configs');
    }
};
