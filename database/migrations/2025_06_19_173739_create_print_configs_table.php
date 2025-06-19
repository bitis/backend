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
        Schema::create('print_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->unsigned();
            $table->boolean('auto_print')->default(false)->comment('自动打印');
            $table->string('name')->nullable()->comment('店铺名称');
            $table->string('endnote')->nullable()->comment('尾注');
            $table->boolean('phone')->default(false)->comment('联系电话');
            $table->boolean('address')->default(false)->comment('联系地址');
            $table->boolean('operator')->default(false)->comment('操作员');
            $table->boolean('member_name')->default(false)->comment('会员名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_configs');
    }
};
