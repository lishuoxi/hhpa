<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('trade_id', 32)->unique();
            $table->string('out_trade_id', 32);
            $table->decimal('amount', 12, 2);
            $table->string('amount_real', 12, 2)->default(0);
            $table->integer('merchant_id');
            $table->integer('account_id')->default(0);
            $table->integer('account_owner_id')->default(0);
            $table->decimal('merchant_rate', 5, 2)->default(0); // 商户费率
            $table->enum('status', ['等待支付', '支付完成', '支付失败']);
            $table->enum('notify_status', ['等待通知', '通知失败', '通知成功']);
            $table->integer('channel_id');
            $table->text('pay_url')->nullable();
            $table->string('notify_url')->default('');
            $table->string('return_url')->default('');
            $table->string('client_ip', 32)->default('');
            $table->timestamp('success_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
