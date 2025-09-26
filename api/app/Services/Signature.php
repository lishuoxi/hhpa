<?php namespace App\Services;

use DB;
use Log;
use App\Models\Meta;
use App\Models\MetaType;

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

        if($sign_res == $data['sign']){
            return true;
        }

        return false;
    }
}
