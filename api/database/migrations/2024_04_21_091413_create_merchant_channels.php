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
        Schema::create('merchant_channels', function (Blueprint $table) {
            $table->id();
            $table->integer('merchant_id');
            $table->integer('channel_id');
            $table->decimal('rate', 4, 2)->default(0);
            $table->string('schedule')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_channels');
    }
};
