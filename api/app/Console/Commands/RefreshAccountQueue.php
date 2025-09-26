<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Account;
use App\Models\Channel;
use App\Models\User;
use App\Services\AccountQueue;

class RefreshAccountQueue extends Command
{
    protected $signature = 'app:refresh-account-queue';
    protected $description = 'Command description';

    public function handle()
    {
        $channels = Channel::all();
        $users = User::where('role_id', 2)->get();

        foreach($users as $user){
            $this->info('更新用户: '. $user->username);

            foreach($channels as $channel){
                $this->info('   更新通道: '. $channel->name);

                AccountQueue::create($channel->id, $user->id, true);
                AccountQueue::show($channel->id, $user->id);

                $this->info('');
                $this->info('   更新通道: '. $channel->name.' 完成');
            }
            $this->info('');
        }
    }
}
