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
use App\Services\Signature;
use Log;

class DaifuController extends Controller
{
	function create(Request $request)
	{
        $url = route('daifu_create');

        $merchant = User::find(2);

        $params = [
            'merchant_id'        => $merchant->merchant_id,
            'out_daifu_id'       => 'TS'.str_random(12),
            'amount'             => random_int(100, 20000),
            'bank'               => '中国工商银行',
            'account'            => 'account',
            'account_name'       => 'account_name',
            'notify_url'         => route('test_daifu_notify_url'),
            'timestamp'          => time()
        ];

        $params['sign'] = Signature::sign($params, $merchant->merchant_key);

        $params['withdrawQueryUrl'] = route('test_daifu_withdraw_query_url');
        $params['callToken'] = 'call back token';

        $res = Http::postRequest($url, $params);

        dump($url);
        dump($params);

        //$res = json_decode($res, true);

        dump($res);
    }

    function withdrawQueryUrl(Request $request)
    {
        return 'width_ok';
    }

    function notify_url(Request $request)
    {
        return 'notify_ok';
    }
}
