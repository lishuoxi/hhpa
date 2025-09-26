<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class ProductUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

		User::create([
			'username' => 'admin',
			'realname' => 'admin',
			'token'    => str_random(12),
			'password' => Hash::make('123123'),
			'role_id'  => 1,
		]);

        // 商户测试号
		User::create([
			'username'     => 'merchant',
			'realname'     => 'merchant',
			'merchant_id'  => 'MER'.str_random(8),
			'merchant_key' => str_random(16),
			'token'        => str_random(12),
			'password'     => Hash::make('123123'),
			'role_id'      => 2,
		]);

        // 代理
		User::create([
			'username'     => 'agent',
			'realname'     => 'agent_0',
			'token'        => str_random(12),
			'password'     => Hash::make('123123'),
			'role_id'      => 3,
		]);
    }
}
