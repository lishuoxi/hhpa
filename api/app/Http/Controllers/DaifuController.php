<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Daifu;
use App\Models\Channel;
use App\Models\MerchantChannel;
use App\Models\User;

use App\Services\Signature;
use App\Services\TradeService;
use App\Services\DaifuService;
use App\Services\CashflowService;
use Validator;
use Str;
use Log;

class DaifuController extends Controller
{
    function create(Request $request)
    {
        Log::info('daifu trade create');
        Log::info($request->all());

		$validator  = Validator::make(request()->all(), [
			'merchant_id'        => 'required|exists:users,merchant_id',
			'out_daifu_id'       => 'required|unique:daifus,out_daifu_id',
			'amount'             => 'required|min:1|max:100000',
            'timestamp'          => 'required',
            'notify_url'         => 'required',
            'sign'               => 'required',
		], [
			'merchant_id.required'        => '商户号不能为空',
			'out_daifu_id.required'       => '商户订单号不能为空',
			'out_daifu_id.unique'         => '商户订单号重复',
			'amount.required'             => '金额不能为空',
			'amount.min|amount.max'       => '金额不正确',
			'timestamp.required'          => '时间戳不能为空',
			'notify_url.required'         => 'notify_url不能为空',
			'sign.required'               => '签名不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
        // timestamp
        $t0 = time();
        if(abs($request->timestamp-$t0) > 20){
            //return $this->fail('错误00: 系统错误');
        }

        // 商户
        $merchant = User::where('merchant_id', $request->merchant_id)->first();
        if(empty($merchant) || $merchant->role_id != 2 || $merchant->status == '冻结'){
            return $this->fail('错误01:商户不存在');
        }

        // 校验签名
        $valid = Signature::valid($request->except(['withdrawQueryUrl', 'callToken']), $merchant->merchant_key);
        if(!$valid){
            return $this->fail('错误02:签名错误');
        }

        $trade = Daifu::create([
            'merchant_id'        => $merchant->id,
            'daifu_id'           => 'DF'.create_id(),
            'out_daifu_id'       => $request->out_daifu_id,
            'amount'             => $request->amount,
            'bank'               => if_else($request->bank),
            'account'            => if_else($request->account),
            'account_name'       => if_else($request->account_name),
            'notify_url'         => if_else($request->notify_url),
            'fancha_url'         => if_else($request->withdrawQueryUrl),
            'call_token'         => if_else($request->callToken),
			'status'			=> '反查成功'
        ]);

        $datas = [
            'daifu_id'           => $trade->daifu_id,
            'out_daifu_id'       => $trade->out_daifu_id,
            'amount'             => $trade->amount,
            'bank'               => $trade->bank,
            'account'            => $trade->account,
            'account_name'       => $trade->account_name,
            'status'             => $trade->status,
        ];

        $datas['sign']  = Signature::sign($datas, $merchant->merchant_key);

        $user = User::where('id', $merchant->id)->first();

        Log::info('daifu');

        //CashflowService::daifuCreate($user, -($trade->amount+DaifuService::$fee), '代付:'.$trade->daifu_id.','.$trade->out_daifu_id);

        /*
        $user->incrementEach([
            'daifu_balance'      => -$trade->amount-DaifuService::$fee,
            'daifu_balance_lock' => $trade->amount+DaifuService::$fee,
        ]);
         */

        $amount_change = $trade->amount + DaifuService::$fee;

        user_daifu_balance_change($merchant->id, -$amount_change, $amount_change);

        return $this->success('处理成功', $datas);
    }

    function query(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'daifu_id'    => 'required',
			'timestamp'   => 'required',
            'sign'        => 'required',
		], [
			'daifu_id.required'    => '商户号不能为空',
			'timestamp.required'   => '时间戳不能为空',
			'sign.required'        => '签名不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $trade = Daifu::where('out_daifu_id', $request->daifu_id)->first();
        //$trade = Daifu::where('daifu_id', $request->daifu_id)->first();

        if(empty($trade)){
            return $this->fail('订单不存在');
        }

        $merchant = User::where('id', $trade->merchant_id)->first();
        if(empty($merchant)){
            return $this->fail('订单不存在0');
        }

        // 校验签名
        $valid = Signature::valid($request->all(), $merchant->merchant_key);
        if(!$valid){
            return $this->fail('错误02:签名错误');
        }
			
		$status_map = [
			'等待反查' => '1',
			'反查成功' => '2',
			'反查失败' => '4',
			'处理成功' => '16',
			'处理失败' => '8',
		];

        $datas = [
            'daifu_id'     => $trade->daifu_id,
            'out_daifu_id' => $trade->out_daifu_id,
            'amount'       => $trade->amount,
            'bank'         => $trade->bank,
            'account'      => $trade->account,
            'account_name' => $trade->account_name,
            'status'       => $status_map[$trade->status],
        ];

        $datas['sign'] = Signature::sign($datas, $merchant->merchant_key);

        return $this->success('查询成功', $datas);
    }

    function moneyQuery(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'merchant_id'    => 'required',
			'timestamp'   => 'required',
            'sign'        => 'required',
		], [
			'merchant_id.required' => '商户号不能为空',
			'timestamp.required'   => '时间戳不能为空',
			'sign.required'        => '签名不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

        $user = User::where('merchant_id', $request->merchant_id)->first();

        if(empty($user)){
            return $this->fail('用户不存在');
        }

        // 校验签名
        $valid = Signature::valid($request->all(), $user->merchant_key);
        if(!$valid){
            return $this->fail('错误02:签名错误');
        }

        $datas = [
            'merchant_id' => $user->merchant_id,
            'money'       => $user->daifu_balance,
        ];

        $datas['sign'] = Signature::sign($datas, $user->merchant_key);

        return $this->success('查询成功', $datas);
    }
}
