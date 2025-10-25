<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginRecord;
use App\Models\MerchantChannel;
use App\Models\AccountOwnerChannel;

use App\Services\Captcha\Captcha;
use App\Services\AuthAdmin;
use Agent;
use Log;
use Hash;
use Validator;
use App\Rules\Captcha as CaptchaRule;
use Google2FA;

class SessionController extends Controller
{
    function login(Request $request)
	{
		$loginRecord = LoginRecord::create([
			'username' => request('username', ''),
			'os'       => Agent::platform(),
			'browser'  => Agent::browser(),
			'device'   => Agent::device(),
			'ip'       => getIp(),
			'type'     => 1,
			'comments' => '',
		]);

		$validator = Validator::make(request()->all(), [  
			'key'      => 'required',
			'code'  => ['required', new CaptchaRule],
			'password' => 'required|min:6,max:12',
			'username' => 'required|max:64,exists:users,username',
		], [
			'username.required' => '用户名不能为空',
			'username.exists'   => '用户或密码错误',
			'captcha.required'  => '请输入验证码',
			'key.required'      => '数据错误',
			'password.required' => '请输入账号密码',
			'password.min'      => '账号密码不正确',
			'password.max'      => '账号密码不正确',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$user = User::where('username', $request->username)->first();
        if(empty($user)){
			return $this->fail('用户名或密码错误');
        }

		$res = Hash::check($request->password, $user->password);
		if(!$res){
			return $this->fail('用户名或密码错误');
		}

        if(!empty($user->admin_secure_ips)){
            $ip = getIp();
            $pos = str_pos($user->admin_secure_ips, $ip);
            if($pos !== false){
                return $this->fail('登陆失败');
            }
        }

        if(!empty($user->google_token)){
            $valid = Google2FA::verifyKey($user->google_token, $request->google_code);
            if(!$valid || empty($request->google_code)){
                //return $this->fail('谷歌验证失败');
            }
        }
        if(empty($user->google_token) && !empty($request->google_code)){
            //return $this->fail('谷歌验证失败');
        }

		$menus = [];
		$menus = array_merge($menus, $user->role->menus->toArray());

		$loginRecord->update(['type' => 0]);
		return [
			"code"=> 0,
			"message"=> "登录成功",
			"data"=> [
				"userinfo" => $user,
				"menus"    => $menus,
			]
		];
	}

	function captcha(Request $request)
	{
		$captha = new Captcha;
		$captha->withConfig('uniqid', $request->uuid);

		$captha->makeCode();
		return $captha->showImage();
	}

	function user()
	{
		$admin = AuthAdmin::admin();

        if(empty($admin)){
            return $this->fail('获取失败');
        }

		$menus = [];
		$menus = array_merge($menus, $admin->role->menus->toArray());

        $datas = [
            'menus'                 => $menus,
            'role'                  => $admin->role->toArray(),
            'username'              => $admin->username,
            'realname'              => $admin->realname,
            'balance'               => $admin->balance,
            'balance_lock'          => $admin->balance_lock,
            'secure_password_seted' => empty($admin->secure_password) ? 0 : 1,
            'google_seted'          => empty($admin->google_token) ? 0 : 1,
        ];

        if($admin->role_id == 2){
            $datas['merchant_id'] = $admin->merchant_id;
            $datas['merchant_key'] = $admin->merchant_key;

            $datas['channels'] = MerchantChannel::with(['channel'])
                ->where('merchant_id', $admin->id)
                ->get();
        }
        if($admin->role_id == 4){
            $datas['account_types'] = AccountOwnerChannel::with(['channel'])
                ->where('account_owner_id', $admin->id)
                ->get();
        }

		return [
			'code'    => 0,
			'message' => '操作成功',
			'data'    => $datas
		];
	}

	function password(Request $request)
	{
		\Log::info('password', $request->all());
		$validator = Validator::make(request()->all(), [  
			'oldPassword' => 'required|min:6,max:12',
			'password'    => 'required|min:6,max:12', 
			'password2'   => 'required|min:6,max:12',
		], [
			'oldPassword.required' => '请输入原密码',
			'oldPassword.min'      => '原密码输入错误',
			'oldPassword.max'      => '原密码输入错误',
			'password.required'    => '新密码必须6-12个字符',
			'password.max'         => '新密码必须6-12个字符',
			'password.min'         => '新密码必须6-12个字符',
			'password2.required'   => '密码两次输入不一致',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		if($request->password != $request->password2){
			return $this->fail('两次输入不一致');
		}

		$admin = AuthAdmin::admin();

		if(!Hash::check($request->oldPassword, $admin->password)){
			return $this->fail('原密码输入不正确');
		}

		User::where('id', $admin->id)
			->update([
				'password' => Hash::make($request->password)
			]);

		return [
			'code'    => 0,
			'message' => '操作成功',
		];
	}

	function updateInfo(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'realname'        => 'required',
			//'admin_secure_ips' => 'required',
		], [
			'realname.required' => '请输入真实名称',
			//'admin_secure_ips.required'   => '请输入安全ip',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$admin = AuthAdmin::admin();

		User::where('id', $admin->id)
			->update([
				'realname'         => $request->realname,
				'admin_secure_ips' => empty($request->admin_secure_ips) ? '' : $request->admin_secure_ips,
			]);

		return [
			'code'    => 0,
			'message' => '操作成功',
		];
	}

	function resetSecurePwd(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'new_pwd'         => 'required|min:6,max:32',
			'new_pwd_confirm' => 'required|min:6,max:32',
		], [
			'new_pwd.required'         => '请输入新支付密码',
			'new_pwd_confirm.required' => '请确认新支付密码',
			'new_pwd.min' => '支付密码不少于6位',
			'new_pwd.max' => '支付密码不多于32位',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$admin = AuthAdmin::admin();

        if($request->new_pwd != $request->new_pwd_confirm){
            return $this->fail('两次输入的密码不一致');
        }

        if(!empty($admin->secure_password)){
            if(empty($request->old_pwd)){
                return $this->fail('请输入原支付密码');
            }

            $res = Hash::check($request->old_pwd, $admin->secure_password);
            if(!$res){
                return $this->fail('原支付密码错误');
            }
        }
        else {
            if(!empty($request->old_pwd)){
                return $this->fail('验证错误');
            }
        }

		User::where('id', $admin->id)
			->update([
                'secure_password'         => Hash::make($request->new_pwd),
			]);

		return [
			'code'    => 0,
			'message' => '操作成功',
		];
	}
}
