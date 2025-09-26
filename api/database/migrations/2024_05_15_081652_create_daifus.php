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
        Schema::create('daifus', function (Blueprint $table) {
            $table->id();
            $table->string('daifu_id', 32);
            $table->string('out_daifu_id', 32);
            $table->integer('merchant_id');
            $table->decimal('amount');
            $table->string('account_name')->default('');
            $table->string('bank')->default('');
            $table->string('account')->default('');
            $table->string('note')->default('');
            $table->string('notify_url')->default('');
            $table->string('fancha_url')->default('');
            $table->string('call_token')->default('');
            $table->enum('status', ['等待反查', '反查成功', '反查失败', '处理成功', '处理失败']);
            $table->enum('notify_status', ['等待回调', '回调成功', '回调失败']);
            $table->timestamp('success_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daifus');
    }
};
