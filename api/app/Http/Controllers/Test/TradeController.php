<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Services\AuthAdmin;
use App\Services\Http;
use App\Services\Signature;
use Log;

class TradeController extends Controller
{
	function create(Request $request)
	{
        $url = route('trade_create');
		$merchant_id = $request->get('merchant_id', 2);
		$channel_code = $request->get('merchant_code', 'alipay_qrcode');

        $merchant = User::find(2);

        $params = [
            'merchant_id'  => $merchant->merchant_id,
            'out_trade_id' => 'TS'.str_random(12),
            'amount'       => '1000',
            'pay_type'     => 'alipay_qrcode',
            'notify_url'   => route('test_trade_notify_url'),
            'timestamp'    => time()
        ];

        $params['sign'] = Signature::sign($params, $merchant->merchant_key);

        $res = Http::postRequest($url, $params);

        dump($url);
        dump($params);

        $res = json_decode($res, true);

        dump($res);
    }
	
	function query(Request $request)
	{
        $url = route('trade_query');
		
		$trade = Trade::where('trade_id', $request->trade_id)->first();
		if(empty($trade)){
			$trade = Trade::where('out_trade_id', $request->trade_id)->first();
			
			if(empty($trade)){
				return '获取订单失败';
			}
		}

        $merchant = User::find($trade->merchant_id);

        $params = [
            'merchant_id'  => $merchant->merchant_id,
            'trade_id' 		=> $trade->trade_id,
            'timestamp'    => time()
        ];

        $params['sign'] = Signature::sign($params, $merchant->merchant_key);

        $res = Http::postRequest($url, $params);

        dump($url);
        dump($params);

        $res = json_decode($res, true);

        dump($res);
    }

    function notifyUrl(Request $request)
    {
        return 'SUCCESS';
    }
}
