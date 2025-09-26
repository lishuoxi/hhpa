<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daifu extends Model
{
    protected $table = 'daifus';
    protected $guarded = ['id'];

    function merchant()
    {
        return $this->belongsTo('App\Models\User', 'merchant_id');
    }

    function account_owner()
    {
        return $this->belongsTo('App\Models\User', 'account_owner_id');
    }
}
