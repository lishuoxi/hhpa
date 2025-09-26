<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

		User::create([
			'username' => 'admin',
			'realname' => 'admin_0',
			'token'    => '123123123',
			'password' => Hash::make('123123'),
			'role_id'  => 1,
		]);

        // 商户测试号
		User::create([
			'username'     => 'merchant',
			'realname'     => 'merchant_0',
			'merchant_id'  => '2024010101',
			'merchant_key' => 'a2e8sdEDtenwer3E',
			'token'        => '222222',
			'password'     => Hash::make('123123'),
			'role_id'      => 2,
		]);

        // 代理
		User::create([
			'username'     => 'agent',
			'realname'     => 'agent_0',
			'token'        => '333333',
			'password'     => Hash::make('123123'),
			'role_id'      => 3,
		]);

        // 码商
		$owner= User::create([
			'username'     => 'account_owner',
			'realname'     => 'account_owner_0',
			'token'        => '444444',
			'balance'      => 1000000,
			'password'     => Hash::make('123123'),
			'role_id'      => 4,
		]);

        // 码商
		User::create([
            'pid'      => $owner->id,
			'username' => 'account_owner11',
			'realname' => 'account_owner_11',
			'token'    => '777777',
			'password' => Hash::make('123123'),
			'role_id'  => 4,
		]);

		User::create([
            'pid'      => $owner->id,
			'username' => 'account_owner12',
			'realname' => 'account_owner_12',
			'token'    => '555555',
			'password' => Hash::make('123123'),
			'role_id'  => 4,
		]);

		User::create([
            'pid'      => $owner->id,
			'username' => 'account_owner13',
			'realname' => 'account_owner_13',
			'token'    => '666666',
			'password' => Hash::make('123123'),
			'role_id'  => 4,
		]);
    }
}
