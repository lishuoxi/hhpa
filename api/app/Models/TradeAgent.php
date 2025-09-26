<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeAgent extends Model
{
    protected $table = 'trade_agents';
    protected $guarded = ['id'];

    function agent()
    {
        return $this->belongTo('App\Models\User', 'agent_id');
    }

    function trade()
    {
        return $this->belongTo('App\Models\Trade', 'trade_id');
    }
}
