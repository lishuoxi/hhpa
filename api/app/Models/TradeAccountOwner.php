<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeAccountOwner extends Model
{
    protected $table = 'trade_account_owners';
    protected $guarded = ['id'];

    function account_owner()
    {
        return $this->belongTo('App\Models\User', 'account_owner');
    }

    function trade()
    {
        return $this->belongTo('App\Models\Trade', 'trade_id');
    }
}
