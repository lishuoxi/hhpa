<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DaifuTrade;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Services\AuthAdmin;
use App\Services\Http;
use Log;

class DaifuTradeController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new DaifuTrade();
        $q = $q->with(['merchant']);

		!empty($request->merchant_id) && $q = $q->where('merchant_id', $request->merchant_id);
		!empty($request->account) && $q = $q->where('account', 'like', '%'.$request->account.'%');
		!empty($request->start_at) && $q = $q->where('created_at', '>=', $request->start_at);
		!empty($request->end_at) && $q = $q->where('created_at', '<=', $request->end_at);

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;
        $lists = $q->offset($offset)->limit($limit)->orderby($sort, $order)->get();

        return $this->success('获取成功', [
            'count'     => $total,
            'totalPage' => $total_page,
            'list'      => $lists,
            'limit'     => $limit,
            'page'      => $page,
        ]);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'amount' => 'required',
		], [
			'amount.required'         => '金额不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $admin = AuthAdmin::admin();
        if(empty($admin) || $admin->role_id != 2){
            return $this->fail('数据错误');
        }

        if($admin->balance < $request->account){
            //return $this->fail('余额不足');
        }

		$params = [
            'daifu_trade_id'     => 'DF'.create_id(),
            'out_daifu_trade_id' => 'ODF'.create_id(),
            'merchant_id'        => $admin->id,
			'amount'             => $request->amount,
			'bank_name'          => if_else($request->bank_name),
			'account'            => if_else($request->account),
			'account_name'       => if_else($request->realname),
			'note'               => if_else($request->note),
		];

		DaifuTrade::create($params);

        User::where('id', $admin->id)->increment('balance_lock', $request->amount);
        User::where('id', $admin->id)->decrement('balance', $request->amount);

        return $this->success('创建成功');
	}

	function updateStatus(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'     => 'required',
			'status' => 'required',
		], [
			'id.required'     => '数据不存在',
			'status.required' => '数据错误',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $trade = DaifuTrade::where('id', $request->id)->first();

        if($trade->status != '等待处理'){
            return $this->fail('操作失败');
        }

        if($request->status == '处理成功'){
            DaifuTrade::where('id', $request->id)
                ->update([
                    'status'     => $request->status,
                    'success_at' => Carbon::now()
                ]);

            User::where('id', $trade->merchant_id)
                ->decrement('balance_lock', $trade->amount);

            if(!empty($trade->notify_url)){
                $datas = [
                    'daifu_trade_id'     => $trade->daifu_trade_id,
                    'out_daifu_trade_id' => $trade->out_daifu_trade_id,
                    'amount'             => $trade->amount,
                    'bank'               => $trade->bank,
                    'account'            => $trade->account,
                    'account_name'       => $trade->account_name,
                    'status'             => $trade->status,
                ];

                $datas['sign'] = Signature::sign($datas, $merchant->merchant_key);

                $res = Http::postRequest($daifu->notify_url, $datas);

                \Log::info('notify return');
                \Log::info($res);
            }

        } else {
            DaifuTrade::where('id', $request->id)
                ->update([
                    'status' => $request->status,
                ]);
        }

		return $this->success('操作成功');
	}
}
