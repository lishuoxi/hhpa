<?php namespace App\Services\Pay;
use App\Services\Pay\Pay;
use App\Models\Account;
use Log;

class AlipayH5 extends Pay
{
    /**
     * 发起订单
     * @return array
     */
    public function doPay($trade, $channel, $account)
    {
        return [
            'pay_url' => route('trade_detail', ['trade_id'=>$trade->trade_id]),
        ];
    }
    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @return 提交表单HTML文本
     */
    protected function buildRequestForm($para_temp) {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='https://openapi.alipay.com/gateway.do?charset=utf-8' method='POST'>";
		foreach($para_temp as $key=>$val){
            if (false === $this->checkEmpty($val)) {
                $val = str_replace("'","&apos;",$val);
                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }		
		}
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }

    protected function generateSign($params, $rsaPrivateKey, $signType = "RSA") {
        try{
            $data = $this->sign($this->getSignContent($params), $rsaPrivateKey, $signType);
        }
        catch(ErrorException $e){
            return [];
        }

        return $data;

    }

    protected function sign($data, $rsaPrivateKey, $signType = "RSA") {
        $priKey=$rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, 'utf-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = 'utf-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    public function detail($trade)
    {
        $account = Account::where('id', $trade->account_id)->first();
        if(empty($account)){
            return 'fail';
        }

        $charset = 'utf-8';

        //请求参数
        $requestConfigs = [
            'out_trade_no' => $trade->trade_id,
            //'product_code' => 'QUICK_WAP_WAY',
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
            'total_amount' => $trade->amount_real, //单位 元
            //'subject'      => '家具小件',  //订单标题
            'subject'      => '往来款',  //订单标题
        ];

        $returnUrl = route('trade_return', ['trade_id' => $trade->trade_id]);
        $notifyUrl = route('trade_notify', ['trade_id' => $trade->trade_id]);

        $appId = $account->param1;
        $rsaPrivateKey = $account->param5;

        $commonConfigs = [
            //公共参数
            'app_id'      => $appId,
            'method'      => 'alipay.trade.wap.pay',             //接口名称
            'format'      => 'JSON',
            'return_url'  => $returnUrl,
            'charset'     => $charset,
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'     => '1.0',
            'notify_url'  => $notifyUrl,
            'biz_content' => json_encode($requestConfigs),
        ];
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $rsaPrivateKey, $commonConfigs['sign_type']);

        return $this->buildRequestForm($commonConfigs);
    }
	
	public function rsaCheck($alipayPublicKey, $params) {
        $sign = $params['sign'];
        $signType = $params['sign_type'];
        unset($params['sign_type']);
        unset($params['sign']);
        return $this->verify($alipayPublicKey, $this->getSignContent($params), $sign, $signType);
    }
	
	 function verify($pubKey, $data, $sign, $signType = 'RSA') {
        //$pubKey= $this->alipayPublicKey;
        //$pubKey= $this->alipayPublicKey;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
//        if(!$this->checkEmpty($this->alipayPublicKey)) {
//            //释放资源
//            openssl_free_key($res);
//        }
        return $result;
    }
	
	public function notify($trade, $request)
    {
		Log::info('alipay notify');
		
		$account = Account::where('id', $trade->account_id)->first();
        if(empty($account)){
            return 'fail';
        }
		$alipayPublicKey = $account->param4;
		
		$params = $request->all();
		
		$result = $this->rsaCheck($alipayPublicKey, $params, $params['sign_type']);
		if($result===true && $params['trade_status'] == 'TRADE_SUCCESS'){
			\Log::info('支付宝回调成功');
			
			//处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
			//程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
			return $this->notify_success('success');
		}
		\Log::info('支付宝回调失败');
		return $this->notify_fail('error');
    }
}
