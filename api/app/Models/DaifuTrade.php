<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaifuTrade extends Model
{
    protected $table = 'daifu_trades';
    protected $guarded = ['id'];

    function merchant()
    {
        return $this->belongsTo('App\Models\User', 'merchant_id');
    }
}
