<?php namespace App\Services;

use App\Models\Daifu;
use App\Models\User;
use App\Services\Http;
use App\Services\Signature;
use App\Services\CashflowService;
use Log;
use Carbon\Carbon;

class DaifuService
{
	public static  $fee = 1;
	
    public function fancha($daifu)
    {
        \Log::info('fancha');
        \Log::info($daifu);

        $merchant = User::where('id', $daifu->merchant_id)->first();
        if(empty($merchant)){
            return [
                'code' => -1,
                'msg'  => '商户不存在'
            ];
        }

       /* if($daifu->status != '等待反查'){
            return [
                'code' => -2,
                'msg'  => '订单已反查过'
            ];
        }*/

        $params = [
            'merchantId' => $merchant->merchant_id,
            'money'      => $daifu->amount,
            'orderNo'    => $daifu->daifu_id, // 是哪个?
            'token'      => $daifu->call_token,
            'target'     => $daifu->amount,
            'ownerName'  => $daifu->amountName, // 提款人姓名
        ];

        $url = $daifu->fancha_url;

        $res_raw = Http::postRequest($url, $params);
        $res = json_decode($res_raw, true);

        \Log::info($params);
        \Log::info($res);

        if(empty($res['code']) || empty($res['status']) || $res['code'] != 200 || $res['status'] != 1){
            $daifu->update(['status' => '反查成功'] );
            return [
                'code' => 0
            ];
        }
        $daifu->update(['status' => '反查失败'] );

        return [
            'code' => 0,
            'msg' => '反查失败'
            ];
    }

    static function success($request, $admin)
    {
        $trade = Daifu::find($request->id);

        $trade->update([
            'success_at' => Carbon::now(),
            'status'         => '处理成功',
            'receive_status' => '已提交',
            'voucher'        => $request->voucher,
        ]);

        //User::where('id', $trade->merchant_id)->decrementEach([
            //'daifu_balance_lock' => -$trade->amount-self::$fee,
        //]);

        user_daifu_balance_change($trade->merchant_id, 0, -($trade->amount + self::$fee), '代付成功: '.$trade->daifu_id);
        user_daifu_balance_change($trade->account_owner_id,  $trade->amount + self::$fee, 0, '代付成功: '.$trade->daifu_id);

        if(!empty($trade->notify_url)){
            $datas = [
                'daifu_id'     => $trade->daifu_id,
                'out_daifu_id' => $trade->out_daifu_id,
                'amount'       => $trade->amount,
                'bank'         => $trade->bank,
                'account'      => $trade->account,
                'account_name' => $trade->account_name,
                'status'       => $trade->status,
                'timestamp'    => time(),
            ];

            $merchant = User::where('id', $trade->merchant_id)->first();
            $datas['sign'] = Signature::sign($datas, $merchant->merchant_key);

            //$res = Http::postRequest($trade->notify_url, $datas);

           // \Log::info('notify return');
            //\Log::info($res);
        }
    }

    static function fail($request, $admin_id)
    {
        $trade = Daifu::find($request->id);

        $trade->update([
            'status'         => '处理失败',
        ]);

        //$user = User::where('id', $admin_id)->first();

        /*
        CashflowService::daifuCreate($user, $trade->amount+self::$fee, '代付失败:'.$trade->daifu_id.','.$trade->out_daifu_id);

        $user->incrementEach([
            'daifu_balance'      => ,
            'daifu_balance_lock' => -$trade->amount-self::$fee,
        ]);
         */

        $amount = $trade->amount + self::$fee;

        user_daifu_balance_change($admin_id,  $amount, -$amount, '代付失败: '.$trade->daifu_id);
    }
}
