<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Trade;
use App\Services\TradeService;
use Carbon\Carbon;

class CashflowWriter extends Command
{
    protected $signature = 'app:cashflow-writer';
    protected $description = 'Command description';

    public function handle()
    {

    }
}
