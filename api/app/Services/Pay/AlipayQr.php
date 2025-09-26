<?php namespace App\Services\Pay;

class AlipayQr
{
    public function __construct()
    {
    }

    public function doPay($trade, $channel, $account)
    {
        return [
           'pay_url'  => route('trade_detail', ['trade_id'=>$trade->trade_id]),
        ];
    }

    public function detail()
    {
        return view('qrcode');
    }

    public function notify()
    {
        return 'ok';
    }
}
