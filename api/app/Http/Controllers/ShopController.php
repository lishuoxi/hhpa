<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Channel;
use App\Models\Account;
use App\Models\Trade;
use Log;
use DB;
use App\Services\Http;
use Carbon\Carbon;
use Storage;
use GuzzleHttp\Client;

class ShopController extends Controller
{
    function getAccounts()
    {
        $accounts = Account::all();

        return $this->success('获取成功', $accounts->toArray());
    }

    function accountDetail(Request $request)
    {
        if(empty($request->account_id)){
            return $this->fail('失败');
        }

        $account = Account::find($request->account_id);

        return $this->success('获取成功', $account);
    }
}
