<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recharge;
use App\Models\User;
use Validator;
use App\Services\AuthAdmin;
use Log;
use Carbon\Carbon;

class RechargeController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail('数据错误');
        }

        $q = new Recharge();
        $q = $q->with(['account_owner']);

		!empty($request->charge_id) && $q = $q->where('recharge_id', $request->recharge_id);
		!empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);

        if($admin->role_id != 1){
            $q = $q->where('account_owner_id', $admin->id);
        }

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

	function lists(Request $request)
	{
        $q = new Recharge();
        $q = $q->with(['recharge_owner', 'recharge_type']);

        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail('数据错误');
        }

		!empty($request->charge_id) && $q = $q->where('recharge_id', $request->recharge_id);
		!empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);

        if($admin->role_id != 1){
            $q = $q->where('account_owner_id', $admin->id);
        }

        $lists = $q->get();

        return $this->success('获取成功', $lists);
	}

	function detail(Request $request)
	{
		$id = $request->id;

		$recharge = Recharge::find($id);

        return $this->success('获取成功', $recharge);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'amount'   => 'required',
			'receipts' => 'required',
		], [
            'amount.required'   => '请填写金额',
            'receipts.required' => '请选择充值凭证',
        ]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $admin = AuthAdmin::admin();
        if(empty($admin) || $admin->role_id!=4){
            return $this->fail('数据错误');
        }

		$params = [
            'account_owner_id' => $admin->id,
            'recharge_id'      => 'RC'.create_id(),
			'amount'           => $request->amount,
			'receipts'         => $request->receipts,
			'note'             => if_else($request->note),
		];

		Recharge::create($params);

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

        $recharge = Recharge::where('id', $request->id)->first();

        if($recharge->status != '等待处理'){
            return $this->fail('操作失败');
        }

        if($request->status == '处理成功'){
            Recharge::where('id', $request->id)
                ->update([
                    'status'     => $request->status,
                    'success_at' => Carbon::now()
                ]);

            User::where('id', $recharge->account_owner_id)
                ->increment('balance', $recharge->amount);
        } else {
            Recharge::where('id', $request->id)
                ->update([
                    'status' => $request->status,
                ]);
        }

		return $this->success('操作成功');
	}
}
