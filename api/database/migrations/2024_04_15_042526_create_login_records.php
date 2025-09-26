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
        Schema::create('login_records', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('os');
            $table->string('device');
            $table->string('browser');
            $table->string('ip', 64);
            $table->tinyinteger('type'); // 0成功，1失败，2退出，3刷新token
            $table->string('comments')->default(''); // 0成功，1失败，2退出，3刷新token
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_records');
    }
};
