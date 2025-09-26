<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentMerchant extends Model
{
    protected $table = 'agent_merchants';
    protected $guarded = ['id'];

    function agent()
    {
        return $this->belongTo('App\Models\User', 'agent_id');
    }

    function merchant()
    {
        return $this->belongTo('App\Models\User', 'merchant_id');
    }

    function channel()
    {
        return $this->belongTo('App\Models\Channel', 'channel_id');
    }
}
