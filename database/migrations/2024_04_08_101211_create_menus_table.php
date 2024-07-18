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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('name', 50);
            $table->string('icon', 50)->nullable();
            $table->string('uri')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0:目录,1:菜单,2:按钮');
            $table->string('permission')->nullable();
            $table->boolean('visible')->default(true);
            $table->boolean('children_visible')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->json('permissions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('user_permissions');
    }
};
