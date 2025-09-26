<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountType;
use App\Models\AccountTypeChannel;
use App\Models\AccountOwnerChannel;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        AccountType::truncate();
        AccountTypeChannel::truncate();
        AccountOwnerChannel::truncate();

		AccountType::create([
			'name' => '支付宝H5',
            'code' => 'alipay_h5'
		]);
		AccountType::create([
			'name' => '支付宝扫码',
			'code' => 'alipay_qr',
		]);
		AccountType::create([
			'name' => '银行卡',
			'code' => 'bank',
		]);
		AccountType::create([
			'name' => '聚富宝',
			'code' => 'jufubao_pay',
		]);
		AccountType::create([
			'name' => '当面付',
			'code' => 'dangmianfu',
		]);

        
		AccountType::create([
			'name' => '支付宝扫码uid',
			'code' => 'alipay_uid',
		]);

        AccountTypeChannel::create([
            'channel_id'      => 1, // h5
            'account_type_id' => 1,
        ]);

        AccountTypeChannel::create([
            'channel_id'      => 2, // alipay_qr
            'account_type_id' => 2,
        ]);

        AccountTypeChannel::create([
            'channel_id'      => 3, // bank
            'account_type_id' => 3,
        ]);

        AccountTypeChannel::create([
            'channel_id'      => 4, // bank
            'account_type_id' => 6,
        ]);


        AccountOwnerChannel::create([
            'account_owner_id' => 4,
            'channel_id'       => 1,
            'rate'             => 0.6
        ]);

        AccountOwnerChannel::create([
            'account_owner_id' => 4,
            'channel_id'       => 2,
            'rate'             => 0.7
        ]);

        AccountOwnerChannel::create([
            'account_owner_id' => 4,
            'channel_id'       => 3,
            'rate'             => 0.8
        ]);

    }
}
