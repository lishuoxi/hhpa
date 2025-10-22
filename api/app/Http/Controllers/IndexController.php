<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Channel;
use App\Models\Daifu;
use App\Models\Account;
use App\Models\Trade;
use App\Models\AliNotify;
use App\Services\Captcha\Captcha;
use App\Services\AuthAdmin;
use Agent;
use Log;
use DB;
use Hash;
use Validator;
use App\Rules\Captcha as CaptchaRule;
use Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\Http;
use Carbon\Carbon;
use App\Services\RedisLock;
use App\Services\Signature;
use App\Services\DaifuService;
use App\Services\AccountQueue;
use App\Services\TradeService;
use Storage;
use GuzzleHttp\Client;

class IndexController extends Controller
{
    function getAccount()
    {
        $base_url = 'http://pp.mot222789.com/';
        $url = $base_url.'';
        $params =  Http::getRequest($url);

        $param = [

        ];

        Account::create($param);


    }

    function alipay(Request $request)
	{

         $param=[];
        $param['query']=json_encode($request->query(),JSON_UNESCAPED_UNICODE);
        $param['content']=json_encode($request->all(),JSON_UNESCAPED_UNICODE);
        AliNotify::create( $param);

		return 'success';

    }


    function pay_nofity(Request $request){
        $id=$request->input('id');
    }

    function test(Request $request)
	{
		$merchant_id = 12;
		$merchant_id = 12;
		$channel_id = 2;
		
		$user = User::find($merchant_id);
		dump($user->username);
		
		$channel = Channel::find($channel_id);
		dump($channel->name);
		
		//AccountQueue::create(12, 3);
		AccountQueue::show($channel_id, $merchant_id);
		
		return;
/*
Log::info('123');
Log::info(time());
return time();
*/
		$trade=Trade::find(143);
		TradeService::sendNotify($trade);


		return 'test2';

    }

    function test2()
    {
        return 'test2';
    }

   function upload(Request $request)
    {
        $file = request()->file('file');  

        if(empty($file)){
            return $this->fail('上传错误');
        }

        //判断文件是否上传成功  
        if($file->isValid()){  
            //获取原文件名  
            $originalName = $file->getClientOriginalName();  
            //扩展名  
            $ext = $file->getClientOriginalExtension();  
            //文件类型  
            $type = $file->getClientMimeType();  
            //临时绝对路径  
            $realPath = $file->getRealPath();
            $filename = date('Y-m-d').'/'.uniqid().'.'.$ext;  
            $bool = \Storage::disk('public')->put($filename, file_get_contents($realPath));

            $url = asset(\Storage::url($filename, false));

            return $this->success('上传成功', [
                'ext'          => $ext,
                'type'         => $type,
                'originalName' => $originalName,
                'url'          => $url,
                'realpath'     => $filename
            ]);
        }

        return $this->fail('上传失败', $request->all());
    } 

    function createTest(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'channel_id' => 'required',
			'amount'     => 'required',
		], [
			'channel_id.required' => '请选择通道',
			'amount.required'     => '请输入金额',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $url = route('trade_create');
        $admin = User::find(2);
        $channel = Channel::where('id', $request->channel_id)->first();

        $trade_id = 'OTD'.create_id();

        $datas = [
            'merchant_id'  => $admin->merchant_id,
            'out_trade_id' => $trade_id,
            'amount'       => $request->amount-(random_int(1,max: 5)/100),
            'pay_type'     => $channel->code,
            'timestamp'    => time(),
            'notify_url'   => route('trade_notify', ['trade_id'=>$trade_id]),
        ];

        $datas['sign'] = Signature::sign($datas, $admin->merchant_key);

        $res = Http::postRequest($url, $datas);
        Log::info('test result');
        Log::info($res);

        $res = json_decode($res, true);

        if(empty($res['data']) || empty($res['data']['pay_url'])){
            return $this->fail($res['message']);
        }

		return $this->success('操作成功', $res['data']['pay_url']);
    }
}
