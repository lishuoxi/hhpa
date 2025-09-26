<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * 支付订单表
     */
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id(); // 主键ID，自增

            $table->string('trade_id', 32)->unique()
                ->comment('平台交易单号（唯一标识）');
            
            $table->string('out_trade_id', 32)
                ->comment('商户订单号（外部系统传入）');

            $table->decimal('amount', 12, 2)
                ->comment('应付金额');

            $table->decimal('amount_real', 12, 2)->default(0)
                ->comment('实付金额');

            $table->integer('merchant_id')
                ->comment('商户ID');

            $table->integer('account_id')->default(0)
                ->comment('收款码ID');

            $table->integer('account_owner_id')->default(0)
                ->comment('收款码所属码商ID');

            $table->decimal('merchant_rate', 5, 2)->default(0)
                ->comment('商户费率（百分比）');

            $table->enum('status', ['等待支付', '支付完成', '支付失败'])
                ->comment('支付状态');

            $table->enum('notify_status', ['等待通知', '通知失败', '通知成功'])
                ->comment('异步通知状态');

            $table->integer('channel_id')
                ->comment('支付通道ID');

            $table->text('pay_url')->nullable()
                ->comment('支付跳转链接/二维码地址');

            $table->string('notify_url')->default('')
                ->comment('商户异步通知回调地址');

            $table->string('return_url')->default('')
                ->comment('支付成功后跳转地址');

            $table->string('client_ip', 32)->default('')
                ->comment('客户端IP');

            $table->timestamp('success_at')->nullable()
                ->comment('支付成功时间');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
