<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\AccountTypeChannel;
use Validator;
use Log;

class AccountTypeController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new AccountType();
        $q = $q->with(['channels']);

		!empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');

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
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new AccountType();
        $q = $q->with(['channels']);

		!empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');

        $lists = $q->get();

        return $this->success('获取成功', $lists);
	}

	function detail(Request $request)
	{
		$id = $request->id;

		$user = AccountType::find($id);

        return $this->success('获取成功', $user);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'name'             => 'required',
			'code'             => 'required',
		], [
            'name.required'   => '请填写名称',
            'code.required'   => '请填写代码',
        ]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
		$params = [
			'name'  => $request->name,
			'code'  => $request->code,
		];

		$user = AccountType::create($params);

        return $this->success('创建成功');
	}

	function update(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'               => 'required|exists:account_types,id',
			'name'             => 'required',
			'code'             => 'required',
		], [
			'id.required'               => '数据不存在',
			'id.exists'                 => '数据不存在',
            'name.required'             => '请填写名称',
            'code.required'             => '请填写代码',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

		$params = [
			'name'  => $request->name,
			'code'  => $request->code,
		];

		AccountType::where('id', $request->id)->update($params);

        return $this->success('操作成功');
	}

	function remove(Request $request)
	{
		$id = $request->id;

		if(!empty($id)){
			AccountType::where('id', $id)->delete();
			AccountTypeChannel::where('account_type_id', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function removeBatch(Request $request)
	{
		$ids = $request->ids;

		if(!empty($ids)){
			AccountType::whereIn('id', $ids)->delete();
			AccountTypeChannel::whereIn('account_type_id', $ids)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function ChannelCreate(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'account_type_id' => 'required|exists:account_types,id',
			'channel_id'      => 'required|exists:channels,id',
		], [
			'account_type_id.required' => '数据不存在',
			'account_type_id.exists'   => '数据不存在',
			'channel_id.required'      => '数据不存在',
			'channel_id.exists'        => '数据不存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $s = AccountTypeChannel::where('channel_id', $request->channel_id)
            ->where('account_type_id', $request->account_type_id)
            ->first();

        if(empty($s)){
            AccountTypeChannel::create([
                'channel_id'      => $request->channel_id,
                'account_type_id' => $request->account_type_id,
            ]);

            return $this->success('操作成功');
        }

        return $this->fail('不能重复绑定');
    }

	function ChannelRemove(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'account_type_id' => 'required|exists:account_types,id',
			'channel_id'      => 'required|exists:channels,id',
		], [
			'account_type_id.required' => '数据不存在',
			'account_type_id.exists'   => '数据不存在',
			'channel_id.required'      => '数据不存在',
			'channel_id.exists'        => '数据不存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $s = AccountTypeChannel::where('channel_id', $request->channel_id)
            ->where('account_type_id', $request->account_type_id)
            ->first();

        if(!empty($s)){
            AccountTypeChannel::where('channel_id', $request->channel_id)
                ->where('account_type_id', $request->account_type_id)
                ->delete();

            return $this->success('操作成功');
        }

        return $this->fail('数据不存在');
    }

	function existence(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'field' => 'required',
			'value' => 'required',
		], [
			'field.required' => '字段不存在',
			'value.required' => '值不存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

		$q = AccountType::where($request->field, $request->value);

		if(!empty($request->id)){
			$q = $q->where('id', '<>', $request->id);
		}

		$user = $q->first();

		if(empty($user)){
			return $this->fail('不存在');
		}

		return $this->success('存在');
	}
}
