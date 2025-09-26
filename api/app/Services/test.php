<?php

class Signature
{
    static public function sign($data, $secret)
    {
        if (isset($data['sign'])) unset($data['sign']);
        $signStr = '';
        ksort($data);
        foreach ($data as $key => $value) {
            if ($value !== '' && $value !== null) {
                $signStr .= $key . '=' . $value . '&';
            }
        }
        $signStr .= 'key=' . $secret;

        return strtolower(md5($signStr));
    }

    static public function valid($data, $secret)
    {
        $sign_res = self::sign($data, $secret);
		echo $sign_res;

        if($sign_res == $data['sign']){
            return true;
        }

        return false;
    }
}

$arr = [
 "amount" => "100.00",
  "merchant_id"=>"MCI2318485417968",
  "notify_url"=>"https://api2.919902.xyz/notify/weichuang2pay/notify_res.htm",
  "out_trade_id"=>"P250925183131385gsi",
  "pay_type"=>"bank_qrcode",
  "sign"=>"97ba3d8cf8d15f4733aed99437007f56",
  "timestamp"=>"1758796291"
  ];

  $k = 'KlfgB9BE8iSEVAH5qiGxnMndziXR9OMa';
  $k = 'I8uSdks7fAfddwmdlUALjT5mdu7bkD2F';

$s = Signature::valid($arr, $k);

echo $s;