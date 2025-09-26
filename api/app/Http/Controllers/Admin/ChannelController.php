<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Validator;
use App\Services\Helper;
use App\Services\Http;
use App\Services\Signature;
use Log;
use App\Models\AccountTypeChannel;

class ChannelController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new Channel();

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
        $q = new Channel();

		!empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');

        $sort = request('sort', 'id');
        $order = request('order', 'desc');
        $limit = request('limit', 10);

        $lists = $q->limit($limit)->orderby($sort, $order)->get();

        return $this->success('获取成功', $lists);
	}

	function detail(Request $request)
	{
		$id = $request->id;
		$channel = Channel::find($id);

        return $this->success('获取成功', $channel);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'name' => 'required|unique:channels,name',
			'code' => 'required|unique:channels,code',
		], [
			'name.required'  => '通道名称不能为空',
			'name.unique'    => '通道名称已存在',
			'code.required'  => '通道编码不能为空',
			'code.unique'    => '通道编码已存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
		$params = [
			'name'             => $request->name,
			'code'             => $request->code,
			'amount_max_limit' => $request->amount_max_limit,
			'amount_min_limit' => $request->amount_min_limit,
			'amount_day_limit' => $request->amount_day_limit,
			'fixed_amounts'    => $request->fixed_amounts,
		];

		Channel::create($params);

        return $this->success('创建成功');
	}

	function update(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'   => 'required|exists:channels,id',
			'code' => 'required|unique:channels,code,'.$request->code.',code',
			'name' => 'required|unique:channels,name,'.$request->name.',name',
		], [
			'id.required'   => '通道不存在',
			'id.exists'     => '通道不存在',
			'name.required' => '通道名称不能为空',
			'name.unique'   => '通道名称已存在',
			'code.required' => '通道编码不能为空',
			'code.unique'   => '通道编码已存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
		$params = [
			'name'              => $request->name,
			'code'              => $request->code,
			'amount_max_limit' => $request->amount_max_limit,
			'amount_min_limit' => $request->amount_min_limit,
			'amount_day_limit' => $request->amount_day_limit,
			'fixed_amounts'    => $request->fixed_amounts,
		];

		Channel::where('id', $request->id)->update($params);

        return $this->success('操作成功');
	}

	function remove(Request $request)
	{
		$id = $request->id;

		if(!empty($id)){
			Channel::where('id', $id)->delete();
			AccountTypeChannel::where('channel_id', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function removeBatch(Request $request)
	{
		$ids = $request->ids;

		if(!empty($ids)){
			Channel::whereIn('id', $ids)->delete();
			AccountTypeChannel::where('channel_id', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function updateStatus(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id' => 'required|exists:channels,id',
			'status' => 'required',
		], [
			'id.required' => '通道不存在',
			'status'          => '',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		Channel::where('id', $request->id)
			->update(['status' => $request->status]);

		return $this->success('操作成功');
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

		$q = Channel::where($request->field, $request->value);

		if(!empty($request->id)){
			$q = $q->where('id', '<>', $request->id);
		}

		$channel = $q->first();

		if(empty($channel)){
			return $this->fail('不存在');
		}

		return $this->success('存在');
	}

	function createTest(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'     => 'required|exists:channels,id',
			'amount' => 'required',
		], [
			'id.required'     => '请选择通道',
			'amount.required' => '数据错误',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $url = route('trade_create');
        $admin = User::find(2);
        $channel = Channel::where('id', $request->id)->first();

        $trade_id = 'OTD'.Helper::create_id();

        $datas = [
            'merchant_id'  => $admin->merchant_id,
            'out_trade_id' => $trade_id,
            'amount'       => $request->amount,
            'pay_type'     => $channel->code,
            'timestamp'    => time(),
            'notify_url'   => route('trade_notify', ['trade_id'=>$trade_id]),
        ];

        $datas['sign'] = Signature::sign($datas, $admin->merchant_key);

        $res = Http::postRequest($url, $datas);
        Log::info('test result');
        Log::info($res);

		return $this->success('操作成功', $res);
	}
}
