<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::truncate();

		Role::create([
			'name' => '管理员',
			'code' => 'admin',
			'note' => '管理员',
		]);

		Role::create([
			'name' => '商户',
			'code' => 'merchant',
			'note' => '商户',
		]);

		Role::create([
			'name' => '代理',
			'code' => 'agent',
			'note' => '代理',
		]);

		Role::create([
			'name' => '码商',
			'code' => 'account_owner',
			'note' => '码商',
		]);
    }
}
