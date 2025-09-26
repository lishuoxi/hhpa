<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeChannel extends Model
{
    protected $table = 'account_type_channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id');
    }

    function account_type()
    {
        return $this->belongsTo('App\Models\AccountType', 'account_type_id');
    }
}
