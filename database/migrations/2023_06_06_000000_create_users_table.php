<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('mobile');
            $table->string('store_id');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->string('openid')->nullable();
            $table->string('unionid')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('system_operator')->default(false);
            $table->boolean('status')->default(0);
            $table->boolean('appointment')->default(1)->comment('可以预约');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
