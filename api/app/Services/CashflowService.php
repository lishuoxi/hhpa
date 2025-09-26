<?php namespace App\Services;

use App\Models\Daifu;
use App\Models\Cashflow;
use App\Models\User;
use App\Services\Http;
use App\Services\Helper;
use Log;
use Carbon\Carbon;

class CashflowService
{
    static public function create($user, $amount, $note)
    {
        Cashflow::create([
            'cashflow_id'   => 'CF'.Helper::create_id(),
            'user_id'       => $user->id,
            'amount'        => $amount,
            'amount_before' => $user->balance,
            'amount_after'  => $user->balance + $amount,
            'note'          => $note,
        ]);
    }

    static public function daifuCreate($user, $daifu_amount, $note)
    {
        Log::info('diafu create');
        Cashflow::create([
            'cashflow_id'         => 'CF'.Helper::create_id(),
            'user_id'             => $user->id,
            'daifu_amount'        => $daifu_amount,
            'daifu_amount_before' => $user->daifu_balance,
            'daifu_amount_after'  => $user->daifu_balance + $daifu_amount,
            'note'                => $note,
        ]);
    }
}
