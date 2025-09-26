<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    //use HasFactory;
    protected $table = 'channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    function accounts()
    {
        return $this->belongsToMany('App\Models\Account', 'channel_accounts', 'channel_id', 'account_id');
    }

    function types()
    {
        return $this->belongsToMany('App\Models\AccountType', 'account_type_channels', 'channel_id', 'account_type_id');
    }
}
