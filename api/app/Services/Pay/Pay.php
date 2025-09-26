<?php namespace App\Services\Pay;

use App\Services\Pay\Pay;
use App\Services\Http;
use App\Services\Signature;
use Log;

class Pay
{
    public function __construct()	
    {
    }

    public function doPay($trade, $type, $account)
    {
        return [
            'pay_url'  => ''
        ];
    }

    public function detail($trade)
    {
        return 'ok';
    }

    public function notify($trade, $request)
    {
        return 'ok';
    }
	
	public function notify_success($return_msg){
		return [
			'valid' 	=> true,
			'msg' 		=> $return_msg,
		];
	}
	
	public function notify_fail($msg){
		return [
			'valid' 	=> false,
			'msg' 		=> $msg,
		];
	}
}
