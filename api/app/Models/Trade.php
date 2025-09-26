<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'trades';
    protected $guarded = ['id'];

    function merchant()
    {
        return $this->belongsTo('App\Models\User', 'merchant_id');
    }

    function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id');
    }

    // 订单
    function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }

    function agent()
    {
        return $this->belongsTo('App\Models\User', 'agent_id');
    }

    function account_owner()
    {
        return $this->belongsTo('App\Models\User', 'account_owner_id');
    }
}
