<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $guarded = ['id'];

    function account_owner()
    {
        return $this->belongsTo('App\Models\User', 'account_owner_id');
    }

    function account_type()
    {
        return $this->belongsTo('App\Models\AccountType', 'account_type_id');
    }
}
