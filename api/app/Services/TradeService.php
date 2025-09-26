<?php namespace App\Services;

use DB;
use Log;
use App\Models\Trade;
use App\Models\Channel;
use App\Models\MerchantChannel;
use App\Models\AccountType;
use App\Models\User;
use App\Models\Account;
use App\Models\AgentMerchant;
use App\Models\TradeAgent;
use App\Models\TradeAccountOwner;
use App\Models\AccountOwnerChannel;
use App\Services\AccountQueue;
use App\Services\RedisLock;
use App\Services\Signature;
use Str;
use Carbon\Carbon;

class TradeService
{
   static function doPay($trade, $channel)
   {
       $default_return = [
           'account_id'       => 0,
           'account_owner_id' => 0,
           'pay_url'    => route('trade_detail', ['trade_id'=>$trade->trade_id]),
           //'pay_url'          => ''
       ];

       AccountQueue::create($trade->channel_id, $trade->merchant_id);
       $account_id = AccountQueue::getAccount($trade->channel_id, $trade->merchant_id);
       $account = null;

       $max_try = 5;

       for($i=0; $i<$max_try; $i++){
           if(empty($account_id)){
               Log::info('empty account');
               return $default_return;
           }

           // 判断当前选择收款码是否有未正在处理订单
           /* $trade0 = Trade::where('channel_id', $account_id)
               ->where('id', '<>', $trade->id)
               ->where('status', '等待支付')
               ->first();
           // 若没有的时候, 选中
           if(!empty($trade0)){
               $account_id = AccountQueue::getAccount($trade->channel_id, $trade->merchant_id);
               Log::info('account: '.$account_id. ' used by '. $trade0->trade_id);
               continue; // 成功
           }
            */

           $account = Account::with('account_owner')->where('id', $account_id)->first();
           if(empty($account) || empty($account->account_owner) || $account->account_owner->balance<$trade->amount){
               Log::info('account: '.$account_id. ' 暂时不可用 ');
               $account_id = AccountQueue::getAccount($trade->channel_id, $trade->merchant_id);
               continue;
           }

           if( ($account->amount_min_limit>0 && $trade->amount < $account->amount_min_limit) || 
           ($account->amount_max_limit>0 && $trade->amount > $account->amount_max_limit) ){
               Log::info('account: '.$account_id. ' 超出限额 ');
               $account_id = AccountQueue::getAccount($trade->channel_id, $trade->merchant_id);
               continue;
           }

           // 押金
           user_balance_change($account->account_owner_id, -$trade->amount, $trade->amount, '订单创建: '. $trade->trade_id);

           break;
       }

       if(empty($account_id) || $i==$max_try){
		   Log::info('trade: '.$trade->trade_id. ' 没找到匹配的码 ');
           return $default_return;
       }

       $account_type = AccountType::find($account->account_type_id);

       $pay_class = get_pay_class($account_type->code);
       $pay_res = $pay_class->doPay($trade, $account_type->code, $account);

       $datas =  [
           'account_id'       => $account->id,
           'account_owner_id' => $account->account_owner_id,
           'pay_url'          => empty($pay_res['pay_url']) ? $default_return['pay_url'] : $pay_res['pay_url']
       ];
	   
       //log_info('trade_service返回', $datas);

       return $datas;
   }

   static function success($trade)
   {
       if($trade->status == '支付完成'){
           return;
       }

       $merchant_channel = MerchantChannel::where('merchant_id', $trade->merchant_id)
           ->where('channel_id', $trade->channel_id)
           ->first();
       $rate = empty($merchant_channel) ? 0 : $merchant_channel->rate;

       $is_bu_dan = false;
       if($trade->status != '等待支付'){
           $is_bu_dan = true;
       }

       $trade->update([
           'merchant_rate' => $rate,
           'status'        => '支付完成',
           'success_at'    => Carbon::now(),
       ]);

       $balance_change = $trade->amount*(1.0 - 0.01*$rate);
       // 商户
       user_balance_change($trade->merchant_id, $balance_change, 0, '订单成功_'.$trade->trade_id);

       // 码商
       if(!empty($trade->account_owner_id) && !empty($trade->account_id)){
           $account = Account::where('id', $trade->account_id)->first();
           if(!empty($account)){
               $account_owner_channel = AccountOwnerChannel::firstOrCreate([
                   'account_owner_id' => $trade->account_owner_id,
                   'channel_id'       => $trade->channel_id
               ]);

               $account_owner = User::with('shangji')->where('id', $trade->account_owner_id)->first();

               if(!empty($account_owner) && !empty($account_owner_channel)){
                   $rate_amount = 0.01*$trade->amount*$account_owner_channel->rate;

                   if($is_bu_dan){
                       user_balance_change($account_owner->id, -$trade->amount, 0, '补单_'.$trade->trade_id);
                   }
                   else{
                       user_balance_change($account_owner->id, $rate_amount, -$trade->amount, '订单成功_'.$trade->trade_id);
                   }

                   TradeAccountOwner::create([
                       'trade_id'         => $trade->id,
                       'account_owner_id' => $account_owner->id,
                       'rate'             => $account_owner_channel->rate,
                       'amount'           => $rate_amount,
                       'level'            => 1,
                   ]);

                   if(!empty($account_owner->shangji)){
                       $shangji_channel = AccountOwnerChannel::firstOrCreate([
                               'account_owner_id' => $account_owner->shangji->id,
                               'channel_id'       => $trade->channel_id
                           ]);

                       if(!empty($shangji_channel)){
                           $rate = $shangji_channel->rate - $account_owner_channel->rate;
                           $rate_amount = $rate*$trade->amount*0.01;

                           $account_owner->shangji->increment('balance', $rate_amount);

                           TradeAccountOwner::create([
                               'trade_id'         => $trade->id,
                               'account_owner_id' => $account_owner->shangji->id,
                               'rate'             => $rate,
                               'amount'           => $rate_amount,
                               'level'            => 2,
                           ]);

                           user_balance_change($account_owner->shangji->id, $rate_amount, 0, '下级订单成功_'.$trade->trade_id);
                       }
                   }
               }
           }
       }

       // 代理
       $agents = AgentMerchant::where('merchant_id', $trade->merchant_id)
           //->where('agent_id', $trade->agent_id)
           ->where('channel_id', $trade->channel_id)
           ->get();

       if(!empty($agents)){
           foreach($agents as $agent){
               $rate_amount = $trade->amount*$agent->rate*0.01;

               User::where('id', $agent->agent_id)->increment('balance', $rate_amount);

               TradeAgent::create([
                   'trade_id'   => $trade->id,
                   'agent_id'   => $agent->agent_id,
                   'rate'       => $agent->rate,
                   'amount'     => $rate_amount,
               ]);

               user_balance_change($agent->agent_id, $rate_amount, 0, '代理订单成功_'.$trade->trade_id);
           }
       }

       self::sendNotify($trade);
   }

   static function fail($trade_id)
   {
       $trade = Trade::where('id', $trade_id)->first();
       if(empty($trade) || $trade->status!='等待支付'){
           return;
       }

       $trade->update([
           'status'        => '支付失败',
           'notify_status' => '通知失败',
       ]);

       if(!empty($trade->account_owner_id) && !empty($trade->account_id)){
           user_balance_change($trade->account_owner_id, $trade->amount, -$trade->amount, '订单失败_'.$trade->trade_id);
       }
   }

   static function sendNotify($trade)
   {
       $url = $trade->notify_url;

       if(empty($url)){
           return;
       }

       $merchant = User::where('id', $trade->merchant_id)->first();

       $datas = [
           'merchant_id'  => $merchant->merchant_id,
           'out_trade_id' => $trade->out_trade_id,
           'amount'       => $trade->amount,
           'status'       => $trade->status,
           'timestamp'    => time(),
       ];

       $datas['sign'] = Signature::sign($datas, $merchant->merchant_key);
	   
	   //dump($datas);
	   
       log_info($trade->trade_id.': notify url: '. $url, $datas);

       $res = Http::postRequest($url, $datas);
	   
	   //dump($res);
	   
       if($res == 'SUCCESS'){
           $trade->update(['notify_status' => '通知成功']);
       }
       else {
           log_info($trade->trade_id.': notify result', $res);

           $trade->update(['notify_status' => '通知失败']);
       }
   }
}
