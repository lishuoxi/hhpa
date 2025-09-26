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
        Schema::table('cashflows', function (Blueprint $table) {
            $table->decimal('daifu_amount', 12, 2)->default(0);
            $table->decimal('daifu_amount_before', 12, 2)->default(0);
            $table->decimal('daifu_amount_after', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashflows', function (Blueprint $table) {
            $table->dropColumn('daifu_amount');
            $table->dropColumn('daifu_amount_before');
            $table->dropColumn('daifu_amount_after');
        });
    }
};
