<?php namespace App\Services\Pay;

use App\Services\Pay\Pay;
use App\Services\Http;
use App\Services\Signature;
use Log;

class ShopH5 extends Pay
{
    public function doPay($trade, $type, $account)
    {
        $url = 'http://www.ailen.top';
        $url .= '/pay/create-trade';

        $params = [
            'trade_id'        => $trade_id,
            'out_trade_id'    => $out_trade_id,
            'amount'          => $trade->amount,
            'amount_real'     => $trade->amount_real,
            'account_id'      => $trade->account_id,
            'notify_url'      => route('trade_notify', [ 'trade_id' => $trade->trade_id ]),
            'return_url'      => route('trade_return', [ 'trade_id' => $trade->trade_id ]),
            'client_ip', 32   => $trade->client_ip,
        ];

		Log::info($params);
		
        $res = Http::postRequest($url, $params);

        Log::info($url);
        Log::info($params);
        Log::info($res);

        $res = json_decode($res, true);
        Log::info($res);

        if($res['code'] != 0){
            return [''];
        }

        return [
            'pay_url'  => $res['data']['payUrl']
        ];
    }

    public function detail($trade)
    {
        return 'ok';
    }

	public function notify($trade, $request)
	{
		Log::info('jufubao notify');
		$merchant_key = 'gmegz3eD8vKvV';
		
		 $params = [
			'price' => $request->price,
			'sysOrderNo' => $request->sysOrderNo,
			'merchantOrderNo' => $request->merchantOrderNo,
			'status' => $request->status,
			'fee' => $request->fee,
			'payTime' => $request->payTime,
			'realMoney' => $request->realMoney
			];
			
		$sign = strtoupper( Signature::sign($params, $merchant_key) );

		if($sign != $request->sign){
			Log::info('签名不正确');
			
			return $this->notify_fail('签名不正确'.$sign.' != '. $request->sign);
		}
		
		if($params['status'] != 'PAY_STATUS_SUCCESS'){
			Log::info('状态不正确');
			return $this->notify_fail('状态不正确');
		}
		
		return $this->notify_success('SUCCESS');
	}
}
