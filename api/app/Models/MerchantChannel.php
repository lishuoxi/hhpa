<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantChannel extends Model
{
    //use HasFactory;
    protected $table = 'merchant_channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    function merchant()
    {
        return $this->belongsTo('App\Models\User', 'merchant_id');
    }

    function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id');
    }
}
