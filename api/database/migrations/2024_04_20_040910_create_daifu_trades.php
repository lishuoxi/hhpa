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
        Schema::create('daifu_trades', function (Blueprint $table) {
            $table->id();
            $table->string('daifu_trade_id', 16);
            $table->string('out_daifu_trade_id', 32);
            $table->integer('merchant_id');
            $table->decimal('amount');
            $table->string('account_name')->default('');
            $table->string('bank_name')->default('');
            $table->string('account')->default('');
            $table->string('note')->default('');
            $table->string('notify_url')->default('');
            $table->enum('status', ['等待处理', '处理成功', '处理失败'])->default('等待处理');
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
        Schema::dropIfExists('daifu_trades');
    }
};
