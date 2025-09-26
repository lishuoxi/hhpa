<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\Account;
use Validator;
use App\Services\TradeService;
use App\Services\AuthAdmin;
use Log;
use DB;

class TradePageController extends Controller
{
	function info(Request $request)
	{
        if(empty($request->trade_id)){
            return $this->fail('');
        }

        $trade = Trade::where('trade_id', $request->trade_id)->with('account')->first();
        if(empty($trade)){
            return $this->fail('');
        }

        if(empty($trade->account)){
            return $this->fail('');
        }

        return $this->success('获取成功', [
            'auth_code'         => $trade->account->name,
            'img'         => $trade->account->param1,
            'url'         => $trade->account->param2,
            'amount'      => $trade->amount,
            'amount_real' => $trade->amount_real,
            'status'      => $trade->status,
            'created_at'  => $trade->created_at->format('Y-m-d H:i:s'),
            'return_url'  => $trade->return_url,
        ]);
	}

	function query(Request $request)
	{
        if(empty($request->trade_id)){
            return $this->fail('');
        }

        $trade = Trade::where('trade_id', $request->trade_id)->first();
      
        if(empty($trade)){
            return $this->fail('');
        }
       

        return $this->success('获取成功', [
            'status'     => $trade->status,
          
        ]);
	}

	function payer(Request $request)
	{
        if(empty($request->trade_id)){
            return $this->fail('');
        }

        $trade = Trade::where('trade_id', $request->trade_id)->first();
        if(empty($trade)){
            return $this->fail('');
        }
        if(empty($request->name)){
            return $this->fail('名字不能为空');
        }

        $trade->update([
            'payer' => $request->name
        ]);

        return $this->success('获取成功', [ ]);
	}
}
