<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trade;
use Validator;
use App\Services\TradeService;
use App\Services\AuthAdmin;
use Log;
use DB;

class TradeController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new Trade();

        $admin = AuthAdmin::admin();

        if($admin->role_id == 1){
            $q = $q->with(['merchant', 'channel', 'account', 'account_owner']);
            //$q->select('id', 'trade_id', 'out_trade_id', 'merchant_id', 'channel_id');
        }
        else if($admin->role_id == 2){
            $q = $q->with(['channel'])
                   ->select('id', 'trade_id', 'out_trade_id', 'channel_id', 'merchant_id', 'amount', 
                   'amount_real', 'created_at', 'status', 'notify_status', 'merchant_rate')
                   ->where('merchant_id', $admin->id);
        }
        else if($admin->role_id == 3){
            $q = $q->with(['channel','merchant'])
                   ->select('id', 'trade_id', 'channel_id', 'merchant_id', 'amount', 
                   'amount_real', 'created_at', 'status', 'notify_status', 'merchant_rate');
        }
        else if($admin->role_id == 4){
            $q = $q->with(['channel', 'account', 'account_owner'])
                   ->select('id', 'trade_id', 'channel_id', 'account_id', 'account_owner_id', 
                   'amount', 'amount_real', 'created_at', 'status', 'payer')
                   ->where('account_owner_id', $admin->id)
                   ;
        }
        else{
            return $this->fail('加载失败');
        }

		!empty($request->trade_id) && $q = $q->where('trade_id', 'like', '%'.$request->trade_id.'%');
		!empty($request->out_trade_id) && $q = $q->where('out_trade_id', 'like', '%'.$request->out_trade_id.'%');
		!empty($request->merchant_id) && $q = $q->where('merchant_id', $request->merchant_id);
		!empty($request->channel_id) && $q = $q->where('channel_id', $request->channel_id);
		!empty($request->account_id) && $q = $q->where('account_id', $request->account_id);
		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;

        $trade_all = (clone $q)->select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
                               ->get()->toArray();
        $trade_success = (clone $q)->select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
                                  ->where('status', '支付完成')
                                  ->get()->toArray();

        $lists = $q->offset($offset)->limit($limit)->orderby($sort, $order)->get();

        return $this->success('获取成功', [
            'count'                => $total,
            'totalPage'            => $total_page,
            'list'                 => $lists,
            'limit'                => $limit,
            'page'                 => $page,
            'trade_all_count'      => $trade_all[0]['trade_count'],
            'trade_all_amount'     => $trade_all[0]['amount_total'],
            'trade_success_count'  => empty($trade_success[0]['trade_count']) ? 0 : $trade_success[0]['trade_count'],
            'trade_success_amount' => empty($trade_success[0]['amount_total']) ? 0 : $trade_success[0]['amount_total'],
        ]);
	}

	function confirm(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'   => 'required',
		], [
			'id.required'   => '不存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $trade = Trade::where('id', $request->id)->first();
        if(!empty($trade) && $trade->status!='支付完成'){
            TradeService::success($trade);
            return $this->success('操作成功');
        }

        if(!empty($trade) && $trade->status=='支付完成' && $trade->notify_status != '通知成功'){
            TradeService::sendNotify($trade);
            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
	}
}
