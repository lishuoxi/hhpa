<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Trade;
use App\Services\TradeService;
use Carbon\Carbon;

class TradeFail extends Command
{
    protected $signature = 'app:trade-fail';
    protected $description = 'Command description';

    public function handle()
    {
        $t = Carbon::now()->subMinute(5);
        $trades = Trade::where('status', '等待支付')->where('created_at', '<=', $t)->pluck('id');

        foreach($trades as $trade_id){
            TradeService::fail($trade_id);
        }
    }
}
