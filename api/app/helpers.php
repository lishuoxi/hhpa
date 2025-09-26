<?php 

// 列表转树形
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }

    return $tree;
}


// 打印sql
function beginSql()
{
    DB::connection()->enableQueryLog();
}

function endSql()
{
    $log = DB::getQueryLog();
    Log::info('query log');
    Log::info($log);

    return $log;
}

// 随机字符串
function str_random($length)
{
    //字符组合
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0; $i<$length; $i++) {
        $num=mt_rand(0,$len);
        $randstr .= $str[$num];
    }
    return $randstr;
}


// 数组随机元素
function arr_random($arr)
{
    $i = array_rand($arr, 1);

    return $arr[$i];
}

// 获取客户端IP
function getIp()
{
    //优化判断，在有cdn的情况下
    if(isset($_SERVER['HTTP_CDN_SRC_IP'])){
        //为了兼容百度的CDN，所以转成数组
        $ip = trim($_SERVER['HTTP_CDN_SRC_IP']);

    }elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
        //代理模式下
        $ip = trim($_SERVER['HTTP_X_REAL_IP']);

    }else{
        //前两种情况下都没有ip的时候
        $ip = trim($_SERVER['REMOTE_ADDR']);

    }
    return $ip;
}

// 空字符串不为null
function if_else($str, $default_value = '')
{
    return empty($str) ? $default_value : $str;
}

// 生成订单号之类
function create_id()
{
    $trade_id = date('dHis').sprintf('%05d', random_int(1,99999));

    return $trade_id;
}

// 根据付款码类型生成
function get_pay_class($type_code)
{
    $pay_class = 'App\\Services\\Pay\\'.Str::studly($type_code);
    return new $pay_class();
}

// log
function log_info($msg, $data = [])
{
    App\Services\LogService::info($msg, $data);
}

// user balance change
function user_balance_change($user_id, $_balance, $_balance_lock, $msg='')
{
    $user = App\Models\User::find($user_id);

	if(!empty($user)){
		App\Services\CashflowService::create($user, $_balance, $msg);
	}

    App\Models\User::where('id', $user_id)->incrementEach([
        'balance'      => $_balance,
        'balance_lock' => $_balance_lock,
    ]); // 商户余额

}

function user_daifu_balance_change($user_id, $_daifu_balance, $_daifu_balance_lock, $msg='')
{
    $user = App\Models\User::find($user_id);
    App\Services\CashflowService::daifuCreate($user, $_daifu_balance, $msg);

    App\Models\User::where('id', $user_id)->incrementEach([
        'daifu_balance'      => $_daifu_balance,
        'daifu_balance_lock' => $_daifu_balance_lock,
    ]); // 商户余额
}
