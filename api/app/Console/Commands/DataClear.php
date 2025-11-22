<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\TradeService;
use Carbon\Carbon;
use App\Models\Trade;
use App\Models\Account;
use App\Models\Cashflow;
use App\Models\DaifuTrade;
use App\Models\Daifu;
use App\Models\LoginRecord;
use App\Models\TradeAccountOwner;
use App\Models\User;
use Storage;
use Log;

class DataClear extends Command
{
    protected $signature = 'app:data-clear {days=3}';
    protected $description = '清除数据';

    public function handle()
    {
        Log::info('run-data-clear');
        //$path = Storage::path(create_id().'.sql');
		//shell_exec('mysqldump -uroot -pLIli327847@ pay > '.$path);

        $days = $this->argument('days');

        $t = Carbon::today()->subDays($days);

        Account::where('created_at', '<=', $t)->update(['created_at' => $t ,'updated_at'=> $t]);
        Cashflow::where('created_at', '<=', $t)->delete();
        DaifuTrade::where('created_at', '<=', $t)->delete();
        //Daifu::where('created_at', '<=', $t)->delete();
        LoginRecord::where('created_at', '<=', $t)->delete();

        Trade::where('created_at', '<=', $t)->delete();
        TradeAccountOwner::where('created_at', '<=', $t)->delete();

        User::where('created_at', '<=', $t)->update(['created_at' => $t ,'updated_at'=> $t]);
    }
}
