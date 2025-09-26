<?php namespace App\Services;

use Illuminate\Support\Facades\Redis;

use App\Models\AccountTypeChannel;
use App\Models\Channel;
use App\Models\Account;
use App\Models\User;
use App\Models\MerchantChannel;

use App\Services\RedisLock;
use Log;

class AccountQueue
{
    public function __construct()
    {
    }

    static public function getQueueName($channel_id, $merchant_id)
    {
        return $channel_id.'__'.$merchant_id;
    }

    static public function show($channel_id, $merchant_id)
    {
        $name = self::getQueueName($channel_id, $merchant_id);
        $len = Redis::llen($name);

        $channel = Channel::find($channel_id);
        if(empty($channel)){
            echo '通道不存在';
            return;
        }

        echo '---------<br/>';
        for($i=0; $i<$len; $i++){
            $v = Redis::lindex($name, $i);

            $account = Account::find($v);
            if(empty($v)){
                echo $v.'参数不存在';
                continue;
            }

            echo $v.'('.$account->name.')'.'--';
        }
        echo '<br/>---------';
    }

    static public function create($channel_id, $merchant_id, $force=false)
    {
        $name = self::getQueueName($channel_id, $merchant_id);

        if(!$force){
            $lock = new RedisLock('kk_'.$name, 600);
            //  十分钟一次
            if(!$lock->acquire()){
                return;
            }
        }

        // 清空
        $a = Redis::lpop($name);
        while(!empty($a)){
            $a = Redis::lpop($name);
        }

        // 商户状态
        $user = User::where('id', $merchant_id)->first();
        if(empty($user) || $user->status!='启用'){
            return;
        }

        // 通道状态
        $channel = Channel::where('id', $channel_id)->first();
        if(empty($channel) || $channel->status!='开启'){
            return;
        }

        // 商户, 通道表是否存在
        $merchant_channel = MerchantChannel::where('merchant_id', $merchant_id)->where('channel_id', $channel_id)->first();
        if(empty($merchant_channel)){
            return;
        }

        $type_ids = AccountTypeChannel::where(
            'channel_id', $channel_id)
            ->pluck('account_type_id')->toArray();
			//dump($type_ids);

        $user_ids = User::where('status', '启用')
            ->where('jiedan_status', '开启')
            ->where('role_id', 4)
            ->pluck('id')->toArray();

        $account_ids = Account::whereIn('account_type_id', $type_ids)
            ->whereIn('account_owner_id', $user_ids)
            ->where('status', '开启')
            ->pluck('id')->toArray();
			
		/* Account::whereIn('account_type_id', $type_ids)
            ->whereIn('account_owner_id', $user_ids)
            ->where('status', '开启')->dumpRawSql();
		 dump($account_ids);*/

        foreach($account_ids as $id){
            Redis::rpush($name, $id);
        }
    }

    static public function getAccount($channel_id, $merchant_id)
    {
        $name = self::getQueueName($channel_id, $merchant_id);

        $a = Redis::lpop($name);
        if(!empty($a)){
            Redis::rpush($name, $a);
        }

        return $a;
    }

    static public function updateByChannel($channel_id)
    {
        $merchants = User::where('role_id', 2)->where('status', '启用')->get();
        foreach($merchants as $merchant){
            self::create($channel_id, $merchant->id, true);
        }

    }

    static public function updateByAccount($account)
    {
        $account_type_channels = AccountTypeChannel::where('account_type_id', $account->account_type_id)->get();
        $merchants = User::where('role_id', 2)->where('status', '启用')->get();

        Log::info('码更新:'.$account->id);
        foreach($account_type_channels as $account_type_channel){
            Log::info('码更新通道:'.$account_type_channel->channel_id);
            foreach($merchants as $merchant){
                self::create($account_type_channel->channel_id, $merchant->id, true);
            }
        }
    }
}
