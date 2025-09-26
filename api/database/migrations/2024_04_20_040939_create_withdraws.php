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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->integer('account_owner_id');
            $table->decimal('amount', 12, 2);
            $table->string('note')->default('');
            $table->enum('status', ['等待处理', '处理成功', '处理失败']);
            $table->integer('admin_id')->default(0);
            $table->timestamp('daozhang_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
