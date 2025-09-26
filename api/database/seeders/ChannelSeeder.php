<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Channel;
use App\Models\MerchantChannel;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        Channel::truncate();
        MerchantChannel::truncate();

		// 支付宝h5
		Channel::create([
            'name'         => '支付宝H5',
            'code'         => 'alipay_h5',
		]);

		// 支付宝h5
		Channel::create([
            'name'         => '支付宝扫码',
            'code'         => 'alipay_qrcode',
		]);

		// 银行扫码
		Channel::create([
            'name'         => '银行卡转账',
            'code'         => 'bank_trans',
		]);

        for($i=0; $i<3; $i++){
            MerchantChannel::create([
                'merchant_id' => 2,
                'channel_id'  => $i+1,
                'rate'        => $i+1,
            ]);
        }



    }
}
