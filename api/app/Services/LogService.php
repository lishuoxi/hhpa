<?php namespace App\Services;

use Log;
use Carbon\Carbon;

class LogService
{
    static public function info($msg, $datas)
    {
        if(!empty($datas)){
            Log::info([
                'msg'   => $msg,
                'datas' => $datas,
            ]);
        }
        else {
            Log::info($msg);
        }
    }
}
