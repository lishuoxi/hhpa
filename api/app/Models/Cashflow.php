<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    protected $table = 'cashflows';
    protected $guarded = ['id'];

    function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
