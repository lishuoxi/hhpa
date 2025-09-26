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
            $table->id()->comment('主键ID');

            $table->string('daifu_trade_id', 16)
                ->comment('平台代付单号（内部唯一标识）');

            $table->string('out_daifu_trade_id', 32)
                ->comment('商户代付单号（外部系统传入）');

            $table->integer('merchant_id')
                ->comment('商户ID（关联商户表）');

            $table->decimal('amount', 12, 2)
                ->comment('代付金额');

            $table->string('account_name')->default('')
                ->comment('收款人姓名');

            $table->string('bank_name')->default('')
                ->comment('收款银行名称');

            $table->string('account')->default('')
                ->comment('收款账户（银行卡号/钱包地址）');

            $table->string('note')->default('')
                ->comment('备注（代付用途说明）');

            $table->string('notify_url')->default('')
                ->comment('商户异步回调地址');

            $table->enum('status', ['等待处理', '处理成功', '处理失败'])
                ->comment('代付处理状态');

            $table->enum('notify_status', ['等待回调', '回调成功', '回调失败'])
                ->comment('回调状态');

            $table->timestamp('success_at')->nullable()
                ->comment('代付成功时间');

            $table->timestamps(); // created_at & updated_at
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
