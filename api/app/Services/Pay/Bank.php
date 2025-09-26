<?php namespace App\Services\Pay;

class Bank
{
    public function __construct()
    {
    }

    public function doPay($trade, $type, $account)
    {
        return [
           'pay_url'  => route('trade_detail', ['code'=>$type, 'trade_id'=>$trade->trade_id]),
        ];
    }

    public function detail()
    {
        return 'ok';
    }

    public function notify()
    {
        return 'ok';
    }
}
