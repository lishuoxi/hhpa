<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Validator;
use Log;
use App\Services\AuthAdmin;

class WithdrawController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new Withdraw();
        $q = $q->with(['account_owner']);

		!empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);

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
        $q = new Withdraw();
        $q = $q->with(['account_owner']);

		!empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);

        $lists = $q->get();

        return $this->success('获取成功', $lists);
	}

	function detail(Request $request)
	{
		$id = $request->id;

		$account = Withdraw::find($id);

        return $this->success('获取成功', $account);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'amount' => 'required',
		], [
            'amount.required' => '请填写金额',
        ]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $admin = AuthAdmin::admin();
        if(empty($admin) || $admin->role_id != 4){
            return $this->fail('数据错误');
        }

		$params = [
			'account_owner_id' => $admin->id,
			'amount'           => $request->amount,
			'name'             => $request->name,
		];

		Withdraw::create($params);

        return $this->success('创建成功');
	}

	function updateStatus(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'     => 'required|exists:withdraws,id',
			'status' => 'required',
		], [
			'id.required'     => '支付码不存在',
			'id.exists'     => '支付码不存在',
			'status.required' => '数据错误',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        if(in_array($request->status, ['处理成功', '处理失败'])){
            return $this->fail('数据错误');
        }

		$withdraw = Withdraw::where('id', $request->id)->first();

        if(empty($withdraw) || $withdraw->status!="等待处理"){
            return $this->fail('数据错误');
        }

        $withdraw->update(['status' => $request->status]);

		return $this->success('操作成功');
	}
}
