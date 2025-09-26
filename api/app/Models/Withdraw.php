<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraws';
    protected $guarded = ['id'];

    function user()
    {
        return $this->belongTo('App\Models\User', 'user_id');
    }
}
