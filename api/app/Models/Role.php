<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //use HasFactory;
    protected $table = 'roles';
    protected $guarded = ['roleId'];
    public $timestamps = false;

    function menus()
    {
        return $this->belongsToMany('App\Models\Menu', 'role_menus', 'role_id', 'menu_id', 'id', 'menuId');
    }
}
