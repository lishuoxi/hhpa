<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $table = 'recharges';
    protected $guarded = ['id'];

    function account_owner()
    {
        return $this->belongsTo('App\Models\User', 'account_owner_id');
    }
}
