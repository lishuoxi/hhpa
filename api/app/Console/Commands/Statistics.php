<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trade;
use App\Models\DaifuTrade;

class Statistics extends Command
{
    protected $signature = 'app:statistics';
    protected $description = 'Command description';

    public function handle()
    {
        $trade_all = Trade::select(DB::raw('count(*) as trade_count'))->get()->toArray();

        $trade_all_success = Trade::select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
            ->where('status', '支付完成')
            ->get()
            ->toArray();

        $trade_today = Trade::select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->get()
            ->toArray();

        $trade_today_success = Trade::select(DB::raw('count(*) as trade_count, sum(`amount`) as amount_total'))
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->where('status', '支付完成')
            ->get()
            ->toArray();

        $user = DB::table('users')->whereBetween('created_at',[Carbon::today(),Carbon::tomorrow()])
                                  ->selectRaw('DATE_FORMAT(created_at,"%H") as date,COUNT(*) as value')
                                  ->groupBy('date')->get();

    }
}
