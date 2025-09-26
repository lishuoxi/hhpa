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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->default(0);
            $table->integer('agent_id')->default(0);
            $table->string('username', 64);
            $table->string('realname')->default('');
            $table->decimal('daifu_balance', 12, 2)->default(0);
            $table->decimal('daifu_balance_lock', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('balance_lock', 12, 2)->default(0);
            $table->string('password', 64);
            $table->string('secure_password')->default(''); // 安全密码IP
            $table->string('google_token', 64)->default('');
            $table->string('token', 64);
            $table->string('secure_ips')->default(''); // 接口 安全IP
            $table->string('admin_secure_ips')->default(''); // 后台登录 安全IP
            $table->integer('role_id');
            $table->enum('status', ['启用', '冻结'])->default('启用');
            $table->enum('jiedan_status', ['开启', '关闭'])->default('开启');

            $table->string('merchant_id', 16)->default('');
            $table->string('merchant_key', 32)->default('');

            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
