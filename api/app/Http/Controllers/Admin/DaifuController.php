<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Daifu;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Services\AuthAdmin;
use App\Services\Http;
use App\Services\Signature;
use App\Services\DaifuService;
use DB;
use Log;

class DaifuController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $admin = AuthAdmin::admin();
        if(empty($admin) ){
            return $this->fail('');
        }

        $q = new Daifu();
        if($admin->role_id == 1){
            $q = $q->with(['merchant', 'account_owner']);
        }

		!empty($request->daifu_id) && $q = $q->where('daifu_id', 'like', '%'.$request->daifu_id.'%');
		!empty($request->out_daifu_id) && $q = $q->where('out_daifu_id', 'like', '%'.$request->out_daifu_id.'%');
		!empty($request->merchant_id) && $q = $q->where('merchant_id', $request->merchant_id);
		!empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);
		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        if($admin->role_id == 2){
            $q = $q->where('merchant_id', $admin->id);
        }

        if($admin->role_id == 4){
            $q = $q->where('account_owner_id', $admin->id);
        }

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;

        $trade_all = (clone $q)->select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
                               ->get()->toArray();
        $trade_success = (clone $q)->select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
                                  ->where('status', '处理成功')
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

	function pageRealtime(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $admin = AuthAdmin::admin();
        if(empty($admin) || $admin->role_id!=4){
            return $this->fail('');
        }

        $q = new Daifu();

		!empty($request->account) && $q = $q->where('account', 'like', '%'.$request->account.'%');
		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        $q = $q->where('status', '反查成功')
               ->whereIn('account_owner_id', [0, $admin->id]);

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

        $trade = Daifu::where('id', $request->id)->first();
		
		//$admin = AuthAdmin();

        if($trade->status != '反查成功'){
            return $this->fail('操作失败');
        }

        if($request->status == '处理成功'){
            DaifuService::success($request);
        } else {
            DaifuService::fail($request, $trade->merchant_id);
        }

		return $this->success('操作成功');
	}

	function confirm(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'      => 'required',
			'voucher' => 'required',
		], [
			'id.required'      => '数据不存在',
			'voucher.required' => '上传不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $admin = AuthAdmin::admin();
        if(empty($admin) ){
            return $this->fail('');
        }

        $daifu = Daifu::where('id', $request->id)->first();
        if(empty($daifu) || $daifu->receive_status != '待提交' || $daifu->account_owner_id!=$admin->id){
            return $this->fail('失败');
        }

        DaifuService::success($request, $admin);

		return $this->success('操作成功');
    }

    // 接单
	function receive(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'          => 'required',
		], [
			'id.required' => '数据不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $daifu = Daifu::where('id', $request->id)->first();
        if(empty($daifu) || $daifu->receive_status != '待接单'){
            return $this->fail('');
        }

        $admin = AuthAdmin::admin();
        if(empty($admin) ){
            return $this->fail('');
        }

        //if($admin->daifu_balance < $daifu->amount){
            //return $this->fail('余额不足, 接单失败');
        //}

        $exist = Daifu::where('account_owner_id', $admin->id)
            ->where('receive_status', '待提交')
            ->first();

        if(!empty($exist)){
            return $this->fail('同时只能接一个单, 请先处理好当前单再来接单');
        }

        $daifu->update([
            'receive_status'   => '待提交',
            'account_owner_id' => $admin->id
        ]);

		return $this->success('操作成功');
    }

	function cancel(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'     => 'required',
		], [
			'id.required'     => '数据不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $daifu = Daifu::where('id', $request->id)->first();
        if(empty($daifu) || $daifu->receive_status != '待提交'){
            return $this->fail('');
        }

        $admin = AuthAdmin::admin();
        if(empty($admin) || $daifu->account_owner_id != $admin->id){
            return $this->fail('');
        }

        $daifu->update([
            'receive_status'   => '待接单',
            'account_owner_id' => 0
        ]);

		return $this->success('操作成功');
    }
}
