<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Daifu;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Services\AuthAdmin;
use App\Services\Http;
use Log;

class AlipayController extends Controller
{
	function create(Request $request)
	{
        $params = [
            'gmt_create'       => '205-13 13:33:46',
            'charset'          => 'ut',
            'seller_email'     => 'zm',
            'subject'          => '家件',
            'sign'             => 'iN==',
            'buyer_open_id'    => '006E123Td123Xo5',
            'invoice_amount'   => '10',
            'notify_id'        => '204778',
            'fund_bill_list'   => '[{"amount":"1.00","fundChannel":"ALIPAYACCOUNT"}]',
            'notify_type'      => 'trade_status_sync',
            'trade_status'     => 'TRADE_SUCCESS',
            'receipt_amount'   => '1.00',
            'buyer_pay_amount' => '1.00',
            'app_id'           => '',
            'sign_type'        => 'RSA2',
            'seller_id'        => '',
            'gmt_payment'      => '2024-05-13 13:33:46',
            'notify_time'      => '2024-05-13 13:58:49',
            'merchant_app_id'  => '',
            'version'          => '1.0',
            'out_trade_no'     => 'TR051399575051',
            'total_amount'     => '1.00',
            'trade_no'         => '',
            'auth_app_id'      => '',
            'buyer_logon_id'   => '1871332',
            'point_amount'     => '0.00',
        ];

        $url = 'http://api_h.mq11ay.com/trade/notify/TR051399575051';
        $res = Http::postRequest($url, $params);

        $res = json_decode($res, true);

        return $this->success($res);
    }
}

