<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuthAdmin;
use Log;
use Validator;
use Google2FA;

class GoogleAuthController extends Controller
{
    function create(Request $request)
	{
        $validator = Validator::make(request()->all(), [  
            'google_code' => 'required',
            'verify_code' => 'required|min:6',
        ], [
            'google_code.required' => '数据错误',
            'verify_code.required' => '谷歌验证码不能为空',
            'verify_code.min'      => '谷歌验证码不正确',
        ]);

        if($validator->fails()){
            Log::info($validator->errors()->first());
            return $this->fail($validator->errors()->first());
        }

        $valid = Google2FA::verifyKey($request->google_code, $request->verify_code);

        if(!$valid){
            return $this->fail('验证失败');
        }

        $admin = AuthAdmin::admin();

        User::where('id', $admin->id)
            ->update([
                'google_token' => $request->google_code,
            ]);

        return $this->success('绑定成功');
	}

    function info(Request $request)
	{
        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail('');
        }


        $google_code = Google2FA::generateSecretKey();
        $google_code_url = Google2FA::getQRCodeUrl('hello:'.$admin->username, $admin->username.'@hello.com', $google_code);

        //$qrcode  = QrCode::size(120)->style('round')->generate($google_code);
        //var_dump($qrcode);

        return $this->success('', [
            'google_code'     => $google_code,
            'google_code_url' => $google_code_url,
        ]);
	}
}
