<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trade;
use App\Models\Channel;
use App\Models\MerchantChannel;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;

use App\Services\Signature;
use App\Services\TradeService;
use Validator;
use Str;
use Log;

class TradeController extends Controller
{
    function create(Request $request)
    {
        log_info('trade create request:', $request->all());

		$validator = Validator::make(request()->all(), [  
			'merchant_id'  => 'required',
			'out_trade_id' => 'required|unique:trades,out_trade_id',
			'amount'       => 'required|min:1|max:100000',
            'pay_type'     => 'required',
            'timestamp'    => 'required',
            'notify_url'   => 'required',
            'sign'         => 'required',
		], [
			'merchant_id.required'  => '商户号不能为空',
			'out_trade_id.required' => '商户订单号不能为空',
			'out_trade_id.unique'   => '商户订单号重复',
			'amount.required'       => '金额不能为空',
			'amount.min|amount.max' => '金额不正确',
			'pay_type.required'     => '通道编码不能为空',
			'timestamp.required'    => '时间戳不能为空',
			'notify_url.required'   => 'notify_url不能为空',
			'sign.required'         => '签名不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}
        // timestamp
        $t0 = time();
        if(abs($request->timestamp-$t0) > 20){ // 20s, 时间还是很长的
            return $this->fail('错误00: 系统错误');
        }

        // 商户
        $merchant = User::where('merchant_id', $request->merchant_id)->first();
        if(empty($merchant) || $merchant->role_id != 2 || $merchant->status == '冻结'){
            return $this->fail('错误01:商户不存在');
        }

        // 校验签名
        $valid = Signature::valid($request->all(), $merchant->merchant_key);
        if(!$valid){
            return $this->fail('错误02:签名错误');
        }

        // 通道
        $channel = Channel::where('code', $request->pay_type)->first();
        if(empty($channel)){
            return $this->fail('错误03:通道不存在或限额不正确');
        }

        $amount=$request->amount;

        // 通道限额
        if(!empty($channel->amount_max_limit) && $amount>$channel->amount_max_limit){
            return $this->fail('错误04:订单金额超出限额');
        }
        if(!empty($channel->amount_min_limit) && $amount>$channel->amount_min_limit){
            return $this->fail('错误05:订单金额超出限额');
        }
        if(!empty($channel->fixed_amounts)){
            $amounts = explode($channel->fixed_amounts);
            if(in_array($amount, $amounts)){
                return $this->fail('错误06:订单金额超出限额');
            }
        }

        // 商户是否开通当前通道
        $merchant_channel = MerchantChannel::where('merchant_id', $merchant->id)
            ->where('channel_id', $channel->id)
            ->first();
        if(empty($merchant_channel)){
            return $this->fail('错误07:通道不存在, 请联系管理员');
        }

       // 浮动金额
        $amount_real = $request->amount;
		//\Log::info('fd '.$amount_real);
        //if($channel->floating_amount){
        if($channel->code == 'alipay_h5' && $request->amount<=10.0){
            $r = random_int(2, 8);
            $amount_real = $request->amount + 0.1*$r;
			//\Log::info($amount_real);
            // $amount_real = $request->amount + 1.0*($r-10)/10;
        }

        $trade = Trade::create([
            'channel_id'   => $channel->id,
            'merchant_id'  => $merchant->id,
            'trade_id'     => 'TR'.create_id(),
            'out_trade_id' => $request->out_trade_id,
            'amount'       => $request->amount,
            'rate'         => $merchant_channel->rate,
            'amount_real'  => $amount_real,
            'notify_url'   => $request->notify_url,
            'return_url'   => if_else($request->return_url),
            'client_ip'    => getIp(),
        ]);

        $res = TradeService::doPay($trade, $channel);

        $trade->account_id = $res['account_id'];
        $trade->account_owner_id = $res['account_owner_id'];
        $trade->pay_url = $res['pay_url'];
        $trade->save();

        $datas = [
            'trade_id'     => $trade->trade_id,
            'out_trade_id' => $trade->out_trade_id,
            'type'         => 'direct_url',
            'pay_url'      => $trade->pay_url,
        ];

        $datas['sign']  = Signature::sign($datas, $merchant->merchant_key);

        log_info('trade create return: '.$trade->trade_id);

        return $this->success('处理成功', $datas);
    }

    function query(Request $request)
    {
		$validator = Validator::make(request()->all(), [  
			'merchant_id' => 'required',
			//'trade_id'    => 'required',
			'timestamp'   => 'required',
            'sign'        => 'required',
		], [
			'merchant_id.required' => '商户号不能为空',
			//'trade_id.required'    => '订单号不能为空',
			'timestamp.required'   => '时间戳不能为空',
			'sign.required'        => '签名不能为空',
		]);

		if($validator->fails()){
			return $this->fail($validator->errors()->first());
		}

	if(!empty($request->trade_id)){
		$trade = Trade::where('trade_id', $request->trade_id)->first();
	}
	else if(!empty($request->out_trade_id)){
		$trade = Trade::where('out_trade_id', $request->out_trade_id)->first();
	}

        if(empty($trade)){
		$trade = Trade::where('out_trade_id', $request->trade_id)->first();
		if(empty($trade)){
		    return $this->fail('订单不存在');
		}
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
		
		$statuses = [
			'等待支付' => -1,
			'支付完成' => 1,
			'支付失败' => 2,
		];
		
		$status = $trade->status;
		if($trade->merchant_id == 11){
			$status = $statuses[$status];
		}

        $datas = [
            'status'       => $status,
            'amount'       => $trade->amount,
            'trade_id'     => $trade->trade_id,
            'out_trade_id' => $trade->out_trade_id,
            'timestamp'    => time(),
        ];

        $datas['sign'] = Signature::sign($datas, $merchant->merchant_key);

        return $this->success('查询成功', $datas);
    }

    function detail(Request $request, $trade_id, $code='')
    {
        $trade = Trade::where('trade_id', $trade_id)->first();
        if(empty($trade) || $trade->status != '等待支付'){
            abort(404);
        }

        if(empty($code)){
            $type = Account::with('account_type')->where('id', $trade->account_id)->first();
            if(empty($type || empty($type->account_type))){
                return;
            }
        }
		
		if(empty($type)){
			return '订单错误, 请重新生成订单';
		}
		
        $code = $type->account_type->code;

        //dd($code);
        //dd($type);

        $pay_class = get_pay_class($code);

        return $pay_class->detail($trade);
    }

    function notify(Request $request, $trade_id, $code='')
    {
		Log::info('notify received: '.$trade_id);
		//Log::info($request->all());
        $trade = Trade::where('trade_id', $trade_id)->first();
        if(empty($trade)){
            return '订单不存在';
        }
		
		if($trade->status=='支付完成' || $trade->status=='支付失败'){
            return '订单状态不对';
        }

        if(empty($code)){
            $account = Account::with('account_type')->where('id', $trade->account_id)->first();
            if(empty($account) || empty($account->account_type)){
                return '类型不对';
            }

            $code = $account->account_type->code;
        }

        $pay_class = get_pay_class($code);
        $res = $pay_class->notify($trade, $request);

        if($res['valid']){
            TradeService::success($trade);
        }

        return $res['msg'];
    }

    function returnUrl(Request $request, $trade_id, $code='')
    {
        $trade = Trade::where('trade_id', $trade_id)->first();
        if($trade->status=='支付完成' || $trade->status=='支付失败'){
            return 'fail';
        }

        $pay_class = get_pay_class($code);
        $res = $pay_class->notify($request->all());

        if($res['valid']){
            TradeService::success($trade);
        }

        return $res['result'];
    }
}
