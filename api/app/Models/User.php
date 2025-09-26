<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['password'];

    function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    function merchants()
    {
        return $this->belongsToMany('App\Models\User', 'agent_merchants', 'agent_id', 'merchant_id')
                    ->withPivot('rate', 'channel_id');
    }

    function agents()
    {
        return $this->belongsToMany('App\Models\User', 'agent_merchants', 'merchant_id', 'agent_id')
                    ->withPivot('rate', 'channel_id');
    }

    function shangji()
    {
        return $this->belongsTo('App\Models\User', 'pid');
    }

    // 商户通道
    function channels()
    {
        return $this->belongsToMany('App\Models\Channel', 'merchant_channels', 'merchant_id', 'channel_id')
                    ->withPivot('rate', 'schedule');
    }

    function owner_channels()
    {
        return $this->belongsToMany('App\Models\Channel', 'account_owner_channels', 'account_owner_id', 'channel_id')
                    ->withPivot('rate');
    }
}
