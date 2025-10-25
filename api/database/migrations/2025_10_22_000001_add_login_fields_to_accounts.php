<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->tinyInteger('is_logged_in')->default(0)->after('note');
            $table->dateTime('login_time')->nullable()->after('is_logged_in');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['is_logged_in', 'login_time']);
        });
    }
};

