<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'account_types';
    protected $guarded = ['id'];
    public $timestamps = false;

    function channels()
    {
        return $this->belongsToMany('App\Models\Channel', 'account_type_channels', 'account_type_id', 'channel_id');
    }
}
