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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('account_owner_id');
            $table->integer('account_type_id');
            $table->string('name', 36);
            $table->string('param1')->default('');
            $table->string('param2')->default('');
            $table->string('param3')->default('');
            $table->text('param4')->nullable();
            $table->text('param5')->nullable();
            $table->text('param6')->nullable();
            $table->decimal('amount_max_limit', 12, 2)->default(0);
            $table->decimal('amount_min_limit', 12, 2)->default(0);
            $table->decimal('amount_day_limit', 12, 2)->default(0);
            $table->decimal('times_day_limit', 12, 2)->default(0);
            $table->string('note')->default('');
            $table->enum('status', ['开启', '关闭'])->default('开启');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
