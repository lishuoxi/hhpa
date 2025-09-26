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
        Schema::table('daifus', function (Blueprint $table) {
            $table->string('voucher')->default('');
            $table->integer('account_owner_id')->default(0);
            $table->timestamp('received_at')->nullable();
            $table->enum('receive_status', ['待接单', '待提交', '已提交'])->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daifus', function (Blueprint $table) {
            $table->dropColumn('voucher');
            $table->dropColumn('account_owner_id');
            $table->dropColumn('received_at');
            $table->dropColumn('receive_status');
        });
    }
};
