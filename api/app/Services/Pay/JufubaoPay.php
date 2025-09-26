<?php namespace App\Services\Pay;

use App\Services\Pay\Pay;
use App\Services\Http;
use App\Services\Signature;
use Log;

class JufubaoPay extends Pay
{
    public function __construct()	
    {
    }

    public function doPay($trade, $type, $account)
    {
        //$url = 'https://merchant.efubao888.com';
        $url = 'https://api.eooo88.com';
        $url .= '/api/gateway/generateOrder';

        $pay_type = '1008';


        $merchant_id = 'fd19adc81b';
        $merchant_key = 'gvV';

        $params = [
            'merchantUUID'    => $merchant_id,
            'price'           => $trade->amount,
            'payType'         => $pay_type,
            'merchantOrderNo' => $trade->trade_id,
            'notifyUrl'       => route('trade_notify', [ 'trade_id' => $trade->trade_id ]),
            'returnUrl'       => route('trade_return', [ 'trade_id' => $trade->trade_id ]),
            'remark'          => '下单请求',
        ];


		Log::info($params);
		
        $params['sign'] = strtoupper( Signature::sign($params, $merchant_key) );

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
		$merchant_key = 'gmegz3eD8vKdfLgnX9uADPNoyeIxx1vV';
		
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
