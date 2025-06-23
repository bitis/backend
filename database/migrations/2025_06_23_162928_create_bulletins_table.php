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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamp('show_at')->nullable()->comment('显示时间');
            $table->boolean('top')->default(false)->comment('是否置顶');
            $table->integer('sort_num')->default(0)->comment('排序');
            $table->boolean('is_show')->default(false)->comment('是否显示');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
