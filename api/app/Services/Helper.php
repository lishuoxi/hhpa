<?php namespace App\Services;

use DB;
use Log;
use Str;
use App\Models\Meta;
use App\Models\MetaType;

class Helper
{
	public static function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
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


	public static function beginSql()
	{
		DB::connection()->enableQueryLog();
	}

	public static function endSql()
	{
		$log = DB::getQueryLog();
		Log::info('query log');
		Log::info($log);

		return $log;
	}

	public static function str_random($length)
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

	public static function arr_random($arr)
	{
		$i = array_rand($arr, 1);

		return $arr[$i];
	}

	public static function getIp()
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

	public static function meta_random($type)
	{
		$meta_type = MetaType::where('code', $type)->first();
		if(empty($meta_type)){
			return;
		}

		$ids = Meta::where('type_id', $meta_type->id)->pluck('id')->toArray();

		return self::arr_random($ids);
	}

    public static function if_else($str, $default_value = '')
    {
        return empty($str) ? $default_value : $str;
    }

    public static function create_id()
    {
        $trade_id = date('md').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

        return $trade_id;
    }

    public static function get_pay_class($channel_code)
    {
       $pay_class = 'App\\Services\\Pay\\'.Str::studly($channel_code);
       return new $pay_class();
    }

}

