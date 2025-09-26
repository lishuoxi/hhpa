<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationRecord extends Model
{
    //use HasFactory;
    protected $table = 'operation_records';
    protected $guarded = ['id'];
}
