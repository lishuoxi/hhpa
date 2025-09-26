<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\AccountType;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::truncate();

        /*
		Account::create([
			'account_owner_id' => '4',
			'account_type_id'  => '1',
			'name'             => '支付宝H5',
			'param1'           => 'app_id',
			'param4'           => '公钥',
			'param5'           => '私钥',
		]);
         */

		Account::create([
			'account_owner_id' => '4',
			'account_type_id'  => '2',
			'name'             => '支付宝扫码',
			'param1'           => 'http://localhost/storage/2024-05-08/663a992cbfbfe.png',
			'param2'           => 'http://www.baidu.com',
		]);

		Account::create([
			'account_owner_id' => '4',
			'account_type_id'  => '3',
			'name'             => '银行卡',
			'param1'           => '开户账号',
			'param2'           => '开户行',
			'param3'           => '卡号',
		]);
/*
		Account::create([
			'account_owner_id' => '4',
			'account_type_id'  => '1',
			'name'             => 'H5测试号',
			'param1'           => '2021001111000000',
			'param4'           => 'MIIBIjANBgkqhkiG9......',
			'param5'           => 'MIIEvQIBADANBgkq......=',
		]);
*/

    }
}
