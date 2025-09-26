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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('code', 16);
            $table->integer('amount_max_limit')->default(0);
            $table->integer('amount_min_limit')->default(0);
            $table->integer('amount_day_limit')->default(0);
            $table->boolean('floating_amount')->default(false);
            $table->string('fixed_amounts', 64)->default('');
            $table->enum('status', ['开启', '关闭'])->default('开启');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
