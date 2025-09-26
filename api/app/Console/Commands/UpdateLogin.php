<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Services\TradeService;
use Carbon\Carbon;
use Log;

class UpdateLogin extends Command
{
    protected $signature = 'app:update-login';
    protected $description = '更新用户用户信息';

    public function handle()
    {
        Log::info('run-update-login');
        $users = User::all();

        foreach($users as $user){
            User::where('id', $user->id)->update([
                'token' => str_random(12),
            ]);
        }
    }
}
