<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use App\Models\MerchantChannel;
use App\Models\AccountOwnerChannel;
use Validator;
use Hash;
use App\Services\AuthAdmin;
use App\Services\CashflowService;
use Log;

class UserController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new User();
        $q = $q->with(['role']);
        if($request->role_id == 4){
            $q = $q->with(['shangji']);
        }

        if($request->role_id == 2){
            $q = $q->with(['channels']);
        }
        if($request->role_id == 4){
            $q = $q->with(['owner_channels']);
        }

		!empty($request->username) && $q = $q->where('username', 'like', '%'.$request->username.'%');
		!empty($request->realname) && $q = $q->where('realname', 'like', '%'.$request->realname.'%');
		!empty($request->role_id) && $q = $q->where('role_id', $request->role_id);

        $admin = AuthAdmin::admin();
        if(empty($admin) || ($admin->role_id != 1 && $admin->role_id != 4)){
            return $this->fail('获取失败');
        }
        if($admin->role_id == 4){
            $q = $q->where('pid', $admin->id);
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
        $q = new User();
        $q = $q->with(['role']);

		!empty($request->username) && $q = $q->where('username', 'like', '%'.$request->username.'%');
		!empty($request->role_id) && $q = $q->where('role_id', $request->role_id);

        $lists = $q->get();

        return $this->success('获取成功', $lists);
	}

	function detail(Request $request)
	{
		$id = $request->id;

		$user = User::find($id);

        return $this->success('获取成功', $user);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'username' => 'required|unique:users,username',
			//'email'    => 'email',
			'role_id'    => 'required',
			'password' => 'required|min:6,max:12',
		], [
			'username.required'         => '用户名不能为空',
			'role_id.required'          => '请选择用户所在用户组',
			'username.unique'           => '用户名已存在',
			'password.required'         => '密码为6到12位数字，字母和特殊字符组合',
			'password.min|password.max' => '密码为6到12位数字，字母和特殊字符组合',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
		$params = [
			'username'        => $request->username,
			'realname'        => $request->realname,
			'role_id'         => $request->role_id,
			'password'        => Hash::make($request->password),
			'token'           => str_random(16),
		];

        // 上级
        if(!empty($request->pid)){
            $params['pid'] = $request->pid;
        }

        // 商户新建
        if($request->role_id == 2){
            $params['merchant_id'] = 'MCI'.create_id();
            $params['merchant_key'] = str_random(32);
        }

		$user = User::create($params);

        return $this->success('创建成功');
	}

	function update(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'       => 'required|exists:users,id',
			'username' => 'required|unique:users,username,'.$request->username.',username',
			'realname' => 'required|unique:users,realname,'.$request->realname.',realname',
		], [
			'id.required'       => '用户不存在',
			'id.exists'         => '用户不存在',
			'username.required' => '用户名不能为空',
			'username.unique'   => '用户名已存在',
			'realname.required' => '用户名不能为空',
			'realname.unique'   => '昵称已存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
		$params = [
			'username'       => $request->username,
			'realname'       => $request->realname,
			'password'       => Hash::make($request->password),
		];

        // 上级
        if(!empty($request->pid)){
            $params['pid'] = $request->pid;
        }

		$user = User::where('id', $request->id)->update($params);


        return $this->success('操作成功');
	}

	function remove(Request $request)
	{
		$id = $request->id;

		if(!empty($id)){
			User::where('id', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function getBalance(Request $request)
	{
        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail();
        }

        return $this->success('操作成功', [
            'balance' => $admin->balance
        ]);
	}

	function updateStatus(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id' => 'required|exists:users,id',
			'status' => 'required',
		], [
			'id.required' => '用户不存在',
			'status'          => '',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		User::where('id', $request->id)
			->update(['status' => $request->status]);

		return $this->success('操作成功');
	}

	function updateJiedanStatus(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id' => 'required|exists:users,id',
			'jiedan_status' => 'required',
		], [
			'id.required' => '用户不存在',
			'jiedan_status'          => '',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		User::where('id', $request->id)
			->update(['jiedan_status' => $request->jiedan_status]);

		return $this->success('操作成功');
	}


	function updatePassword(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'   => 'required|exists:users,id',
			//'password' => 'required|min:6,max:12',
		], [
			'id.required' => '用户不存在',
			//'password'        => '密码为6到12位数字，字母和特殊字符组合',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		User::where('id', $request->id)
			//->update(['password' => Hash::make($request->password)]);
			->update(['password' => Hash::make('123456')]);

        return $this->success('重置成功');
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

		$q = User::where($request->field, $request->value);

		if(!empty($request->id)){
			$q = $q->where('username', '<>', $request->id);
		}

		$user = $q->first();

		if(empty($user)){
			return $this->fail('不存在');
		}

		return $this->success('存在');
	}

    function resetGoogle()
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

		$q = User::where($request->field, $request->value);

		if(!empty($request->id)){
			$q = $q->where('username', '<>', $request->id);
		}

		$user = $q->first();

		if(empty($user)){
			return $this->fail('不存在');
		}

		return $this->success('存在');
    }

	function updateGoogle(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'id'   => 'required|exists:users,id',
			//'password' => 'required|min:6,max:12',
		], [
			'id.required' => '用户不存在',
			//'password'        => '密码为6到12位数字，字母和特殊字符组合',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		User::where('id', $request->id)
			//->update(['password' => Hash::make($request->password)]);
			->update(['google_token' => '']);

        return $this->success('重置成功');
	}

    function merchantChannelCreate(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
			'rate'       => 'required',
		], [
			'id.required'         => '请选择商户',
			'id.exists'           => '商户不存在',
			'channel_id.required' => '请选择通道',
			'channel_id.exists'   => '通道不存在',
			'rate.required'       => '请输入费率',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $merchant_channel = MerchantChannel::where('merchant_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(empty($merchant_channel)){
            MerchantChannel::create([
                'merchant_id' => $request->id,
                'channel_id'  => $request->channel_id,
                'rate'        => $request->rate,
            ]);

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function merchantChannelUpdate(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
			'rate'       => 'required',
		], [
			'id.required'         => '请选择商户',
			'id.exists'           => '商户不存在',
			'channel_id.required' => '通道不存在',
			'channel_id.exists'   => '通道不存在',
			'rate.required'       => '请设置费率',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $merchant_channel = MerchantChannel::where('merchant_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(!empty($merchant_channel)){
            $merchant_channel->update([
                'rate'        => $request->rate,
            ]);

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function merchantChannelRemove(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
		], [
			'id.required'         => '商户不存在',
			'id.exists'           => '商户不存在',
			'channel_id.required' => '通道不存在',
			'channel_id.exists'   => '通道不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $merchant_channel = MerchantChannel::where('merchant_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(!empty($merchant_channel)){
            $merchant_channel->delete();

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function accountOwnerChannelCreate(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
			'rate'       => 'required',
		], [
			'id.required'         => '商户不存在',
			'id.exists.required'  => '商户不存在',
			'channel_id.required' => '通道不存在',
			'channel_id.exists'   => '通道不存在',
			'rate.required'       => '通道不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $account_owner_channel = AccountOwnerChannel::where('account_owner_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(empty($account_owner_account_type)){
            AccountOwnerChannel::create([
                'account_owner_id' => $request->id,
                'channel_id'       => $request->channel_id,
                'rate'             => $request->rate,
            ]);

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function accountOwnerChannelUpdate(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
			'rate'       => 'required',
		], [
			'id.required'         => '商户不存在',
			'id.exists'           => '商户不存在',
			'channel_id.required' => '通道不存在',
			'channel_id.exists'   => '通道不存在',
			'rate.required'       => '请设置费率',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $account_owner_channel = AccountOwnerChannel::where('account_owner_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(!empty($account_owner_channel)){
            $account_owner_channel->update([
                'rate'  => $request->rate,
            ]);

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function accountOwnerChannelRemove(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'         => 'required|exists:users,id',
			'channel_id' => 'required|exists:channels,id',
		], [
			'id.required'         => '商户不存在',
			'id.exists'           => '商户不存在',
			'channel_id.required' => '通道不存在',
			'channel_id.exists'   => '通道不存在',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

        $account_owner_channel = AccountOwnerChannel::where('account_owner_id', $request->id)
            ->where('channel_id', $request->channel_id)
            ->first();

        if(!empty($account_owner_channel)){
            $account_owner_channel->delete();

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function editBalance(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'id'           => 'required|exists:users,id',
		], [
			'id.required'     => '用户不存在',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
        $user = User::where('id', $request->id)->first();

        $amount = empty($request->amount) ? 0 : $request->amount;
        $amount_lock = empty($request->amount_lock) ? 0 : $request->amount_lock;
        $daifu_amount = empty($request->daifu_amount) ? 0 : $request->daifu_amount;
        $daifu_amount_lock = empty($request->daifu_amount_lock) ? 0 : $request->daifu_amount_lock;

        if(empty($amount) && empty($daifu_amount) && empty($amount_lock) && empty($daifu_amount_lock)){
            return $this->fail('请输入金额');
        }

        if(!empty($amount) || !empty($amount_lock)){
            user_balance_change($request->id, $amount, $amount_lock, '手动修改余额: '.$user->username);
        }

        if(!empty($daifu_amount) || !empty($daifu_amount_lock)){
            user_daifu_balance_change($request->id, $daifu_amount, $daifu_amount_lock, '手动修改代付余额:'.$user->username);
        }

        return $this->success('操作成功');
    }
}
