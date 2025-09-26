<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 用户表
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            // 主键ID，自增

            $table->integer('pid')->default(0); 
            // 上级用户ID（0 表示无上级，通常用于代理层级关系）

            $table->integer('agent_id')->default(0); 
            // 代理ID（标记商户所属代理）

            $table->string('username', 64); 
            // 用户名（唯一标识）

            $table->string('realname')->default(''); 
            // 真实姓名（默认空字符串）

            $table->decimal('daifu_balance', 12, 2)->default(0); 
            // 代付可用余额（单位：元，精度2位小数）

            $table->decimal('daifu_balance_lock', 12, 2)->default(0); 
            // 代付冻结余额（正在处理的代付资金）

            $table->decimal('balance', 12, 2)->default(0); 
            // 普通账户余额

            $table->decimal('balance_lock', 12, 2)->default(0); 
            // 冻结余额（未结算或处理中资金）

            $table->string('password', 64); 
            // 登录密码（通常存储哈希值）

            $table->string('secure_password')->default(''); 
            // 安全密码（可能用于支付/操作二次验证）

            $table->string('google_token', 64)->default(''); 
            // Google Authenticator 二次验证密钥

            $table->string('token', 64); 
            // 用户登录会话 token（用于API认证）

            $table->string('secure_ips')->default(''); 
            // 允许访问 API 的安全 IP 列表(IP白名单, 以逗号隔开)

            $table->string('admin_secure_ips')->default(''); 
            // 后台管理端登陆的安全 IP 列表

            $table->integer('role_id'); 
            // 角色ID（对应角色权限表）

            $table->enum('status', ['启用', '冻结']); 
            // 用户状态：启用 / 冻结

            $table->enum('jiedan_status', ['开启', '关闭']); 
            // 接单状态：开启 / 关闭（可能用于代付或任务接单）

            $table->string('merchant_id', 16)->default(''); 
            // 商户号（对接外部系统时使用）

            $table->string('merchant_key', 32)->default(''); 
            // 商户秘钥（用于接口签名验证）

            $table->timestamp('last_login_at')->nullable(); 
            // 最后登录时间（可为空）

            $table->timestamps(); 
            // created_at & updated_at 自动维护
        });
    }

    /**
     * Reverse the migrations.
     *
     * 删除 users 表
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
