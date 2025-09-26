<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $is_debug = env('APP_DEBUG', false);

        if($is_debug){
            $this->call(DebugUserSeeder::class); // 用户
        }
        else{
            $this->call(ProductUserSeeder::class); // 用户
        }

        $this->call(RoleSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(ChannelSeeder::class); // 支付通道
        $this->call(AccountTypeSeeder::class); // 支付通道
        $this->call(AccountSeeder::class); // 支付通道
    }
}
