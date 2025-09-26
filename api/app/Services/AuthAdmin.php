<?php namespace App\Services;

use DB;
use Log;
use App\Models\User;

class AuthAdmin
{
	public static function admin()
	{
		$token = request()->header('Authorization');

		//Log::info('token:'.$token);

		if(empty($token)){
			return null;
		}

		$admin = User::where('token', $token)->first();
		if($admin && $admin->status == '启用'){
			return $admin;
		}

		return null;
	}
}

