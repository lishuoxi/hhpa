<?php namespace App\Services\Pay;
use App\Services\Pay\Pay;
use App\Models\Account;
use Log;
use Storage;

class DangmianfuCer extends Pay
{
    /**
     * 发起订单
     * @return array
     */
    public function doPay($trade, $channel, $account)
    {
        $param = $account;
        if(empty($param)){
            return 'fail';
        }
        //=======【证书路径设置，使用公钥证书模式需填写】=======
        $alipay_config = [
            //=======【应用基本信息设置】=======
            //'app_id'            => '2021004148000000', //  支付宝开放平台应用ID
            //'cert_mode'         => 1, // * 是否使用公钥证书模式
            //'alipay_public_key' => '', //* 支付宝公钥
            //'app_private_key'   => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAEZ7dG7kwMJr1syeZSPDwT6AvS4=', //* 应用私钥
            //'app_auth_token'    => '', //* 服务商模式应用授权token //* 只有服务商模式的子商户需要填写

            'app_id'            => $param->param4, //  支付宝开放平台应用ID
            'cert_mode'         => 1, // * 是否使用公钥证书模式
            //'alipay_public_key' => '', //* 支付宝公钥
            'app_private_key'   => $param->param5, //  支付宝开放平台应用ID
            'app_auth_token'    => '', //* 服务商模式应用授权token //* 只有服务商模式的子商户需要填写

            'smid'              => '', //* 只有互联网平台直付通的子商户需要填写 // * 互联网平台直付通子商户ID
            'app_cert_path'     => Storage::disk('public')->path($param->param1), //* 应用公钥证书文件路径 （填写后会使用公钥证书模式，留空使用公钥模式）
            'alipay_cert_path'  => Storage::disk('public')->path($param->param2), // * 支付宝公钥证书文件路径
            'root_cert_path'    => Storage::disk('public')->path($param->param3), //* 支付宝根证书文件路径
            'sign_type'         => "RSA2", //* 签名方式,默认为RSA2
            'charset'           => "UTF-8", // * 编码格式
            'gateway_url'       => "https://openapi.alipay.com/gateway.do", //* 支付宝网关
            'notify_url'        => route('trade_notify', ['trade_id' => $trade->trade_id]),
        ];

        $bizContent = [
            'out_trade_no' => $trade->trade_id, //商户订单号
            'total_amount' => $trade->amount_real, //单位 元
            'subject'      => '往来款',  //订单标题
        ];

        try{
            $aop = new \Alipay\AlipayTradeService($alipay_config);
            $result = $aop->qrPay($bizContent);
            //Log::info('支付宝下单成功！支付二维码链接：');
            //Log::info($result);
            return ['pay_url' => $result['qr_code']];
        }catch(Exception $e){

            return [
                'pay_url' => route('trade_detail', ['trade_id'=>$trade->trade_id]),
            ];

			//return '支付宝下单失败:'.$e->getMessage();
        }		

		if(empty($result) || empty($result['alipay_trade_precreate_response'])){

            return [
                'pay_url' => route('trade_detail', ['trade_id'=>$trade->trade_id]),
            ];
			return '订单错误';
		}

        return [
            'pay_url' => route('trade_detail', ['trade_id'=>$trade->trade_id]),
        ];
    }

    public function detail($trade)
    {
        return '下单错误';
        $param = Account::where('id', $trade->account_id)->first();
        if(empty($param)){
            return 'fail';
        }
        //=======【证书路径设置，使用公钥证书模式需填写】=======
        $alipay_config = [
            //=======【应用基本信息设置】=======
            //'app_id'            => '20210040000000000', //  支付宝开放平台应用ID
            //'cert_mode'         => 1, // * 是否使用公钥证书模式
            //'alipay_public_key' => '', //* 支付宝公钥
            //'app_private_key'   => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCxuraiMm2j1FlDQGErVAIsB6iNreNMwiHABNRX7rPcLt9E1JeOtuV01WVNlBX4DsH4VzTgiPdVkC3lEYhCD8OZZsquTqyQZTsJnDPe6op+9AKJuYVPqUTuhbwvXssMk6GjAVFZ0jm5BvANrKq5MWLxZFRWSMAYSVsisxiZ8O2trV8mRqSE+uvrvixq++lpmGtDMy90g4IJUxTKpkFPnI3J1lJyHqSdiMjpOF8t+HU7CoorBJTzx9jRv7IBiNCEZ7dG7kwMJr1syeZSPDwT6AvS4=', //* 应用私钥
            //'app_auth_token'    => '', //* 服务商模式应用授权token //* 只有服务商模式的子商户需要填写

            'app_id'            => $param->param4, //  支付宝开放平台应用ID
            'cert_mode'         => 1, // * 是否使用公钥证书模式
            //'alipay_public_key' => '', //* 支付宝公钥
            'app_private_key'   => $param->param5, //  支付宝开放平台应用ID
            'app_auth_token'    => '', //* 服务商模式应用授权token //* 只有服务商模式的子商户需要填写

            'smid'              => '', //* 只有互联网平台直付通的子商户需要填写 // * 互联网平台直付通子商户ID
            'app_cert_path'     => Storage::disk('public')->path($param->param1), //* 应用公钥证书文件路径 （填写后会使用公钥证书模式，留空使用公钥模式）
            'alipay_cert_path'  => Storage::disk('public')->path($param->param2), // * 支付宝公钥证书文件路径
            'root_cert_path'    => Storage::disk('public')->path($param->param3), //* 支付宝根证书文件路径
            'sign_type'         => "RSA2", //* 签名方式,默认为RSA2
            'charset'           => "UTF-8", // * 编码格式
            'gateway_url'       => "https://openapi.alipay.com/gateway.do", //* 支付宝网关
            'notify_url'        => route('trade_notify', ['trade_id' => $trade->trade_id]),
        ];

        $bizContent = [
            'out_trade_no' => $trade->trade_id, //商户订单号
            'total_amount' => $trade->amount_real, //单位 元
            'subject'      => '往来款',  //订单标题
        ];

        try{
            $aop = new \Alipay\AlipayTradeService($alipay_config);
            $result = $aop->qrPay($bizContent);
            //Log::info('支付宝下单成功！支付二维码链接：');
            //Log::info($result);
            return view('dangmianfu', ['qrcode_url' => $result['qr_code']]);
        }catch(Exception $e){
			return '支付宝下单失败:'.$e->getMessage();
        }		

		if(empty($result) || empty($result['alipay_trade_precreate_response'])){
			return '订单错误';
		}
    }
	
	public function notify($trade, $request)
    {
        $param = Account::where('id', $trade->account_id)->first();
        if(empty($param)){
            return 'fail';
        }
        //=======【证书路径设置，使用公钥证书模式需填写】=======
        $alipay_config = [
            //=======【应用基本信息设置】=======
            //'app_id'            => '', //  支付宝开放平台应用ID
            //'app_private_key'   => '', //* 应用私钥
            'app_id'            => $param->param4, //  支付宝开放平台应用ID
            'cert_mode'         => 1, // * 是否使用公钥证书模式
            //'alipay_public_key' => '', //* 支付宝公钥
            'app_private_key'            => $param->param5, //  支付宝开放平台应用ID
            'app_auth_token'    => '', //* 服务商模式应用授权token //* 只有服务商模式的子商户需要填写

            'smid'              => '', //* 只有互联网平台直付通的子商户需要填写 // * 互联网平台直付通子商户ID
            'app_cert_path'     => Storage::disk('public')->path($param->param1), //* 应用公钥证书文件路径 （填写后会使用公钥证书模式，留空使用公钥模式）
            'alipay_cert_path'  => Storage::disk('public')->path($param->param2), // * 支付宝公钥证书文件路径
            'root_cert_path'    => Storage::disk('public')->path($param->param3), //* 支付宝根证书文件路径
            'sign_type'         => "RSA2", //* 签名方式,默认为RSA2
            'charset'           => "UTF-8", // * 编码格式
        ];
		
		$params = $request->all();
        $aop = new \Alipay\AlipayTradeService($alipay_config);
        if($aop->check($_POST)) {//验证成功
            return $this->notify_success('success');
        }else{
            return $this->notify_fail('error'); //验证失败
        }
    }
}
