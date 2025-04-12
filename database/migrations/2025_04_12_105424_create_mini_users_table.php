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
        Schema::create('mini_users', function (Blueprint $table) {
            $table->id();
            $table->string('openid')->nullable();
            $table->string('unionid')->nullable();
            $table->string('token')->nullable();
            $table->integer('coin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_users');
    }
};
