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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('store_id');
            $table->string('name')->comment('打印机名称');
            $table->string('sn')->comment('设备编号');
            $table->char('type', 2)->nullable()->comment('打印机类型');
            $table->string('version')->nullable()->comment('打印机固件版本');
            $table->boolean('cutter')->nullable()->comment('是否带切刀，true 有切刀 false 无切刀');
            $table->integer('voice_type');
            $table->integer('volume_level');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
