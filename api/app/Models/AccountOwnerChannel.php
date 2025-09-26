<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountOwnerChannel extends Model
{
    protected $table = 'account_owner_channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    function account_owner()
    {
        return $this->belongsTo('App\Models\User', 'account_owner_id');
    }

    function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id');
    }
}
