<?php

namespace App\Http\Controllers;

abstract class Controller
{
    static function success($msg='', $data=[])
    {
        $data = (empty($data) && is_array($data)) ? (object)array() : $data;

        return response()->json([
            'code'    => 0,
            'message' => $msg,
            'data'    => $data
        ]);
    }

    static function fail($msg='')
    {
        return response()->json([
            'code'    => -1,
            'message' => $msg
        ]);
    }
}
