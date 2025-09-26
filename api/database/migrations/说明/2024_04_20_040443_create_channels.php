<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * 支付通道模型
 *
 * @property int $id 主键ID
 * @property string $name 通道名称（如支付宝、微信）
 * @property string $code 通道编码（系统内部标识）
 * @property int $amount_max_limit 单笔最大限额
 * @property int $amount_min_limit 单笔最小限额
 * @property int $amount_day_limit 每日限额
 * @property bool $floating_amount 是否支持浮动金额
 * @property string $fixed_amounts 固定金额列表（逗号分隔）
 * @property string $status 状态：开启 / 关闭
 */
 
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
            $table->enum('status', ['开启', '关闭']);
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
