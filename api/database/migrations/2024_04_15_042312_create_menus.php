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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('menuId');

            $table->integer('parentId')->default(0); // Object类型，非必须，对应路由的元数据Meta
            $table->string('title')->default('');
            $table->string('path'); // 要以/开头 , 必须要写，
            $table->string('component')->nullable(); // 组件地址要放在views目录下，父级非必须
            $table->boolean('menuType')->default(0); // 0是菜单，1是按钮
            $table->integer('sortNumber')->default(0); // 排序号
            $table->string('authority')->nullable(); // 权限标识
            $table->string('target')->default('_self'); // 打开的位置 
            $table->string('icon')->nullable(); // 非必须
            $table->string('color')->nullable(); // 非必须
            $table->boolean('hide')->default(0); // 为true只注册路由，不显示在左侧菜单，比如独立显示的页面，非必须
            $table->string('active')->default(''); //  比如修改页面，不在侧边显示。打开页面后就没有选中。这个配置选中地址，非必须
            $table->string('meta')->nullable(); // Object类型，非必须，对应路由的元数据Meta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
