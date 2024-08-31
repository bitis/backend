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
        Schema::create('official_account_configs', function (Blueprint $table) {
            $table->id();
            $table->string('account')->comment('标识');
            $table->string('app_id');
            $table->string('secret');
            $table->string('token');
            $table->string('aes_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_account_configs');
    }
};
