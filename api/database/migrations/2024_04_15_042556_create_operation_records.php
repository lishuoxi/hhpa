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
        Schema::create('operation_records', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('description');
            $table->string('url');
            $table->string('request_method');
            $table->string('method');
            $table->string('params');
            $table->string('result');
            $table->string('error');
            $table->string('comments');
            $table->integer('spend_time'); // 请求耗时，单位毫秒
            $table->string('os'); // 
            $table->string('device'); // 
            $table->string('browser'); // 
            $table->string('ip'); // 
            $table->integer('status'); //  0,成功， 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_records');
    }
};
