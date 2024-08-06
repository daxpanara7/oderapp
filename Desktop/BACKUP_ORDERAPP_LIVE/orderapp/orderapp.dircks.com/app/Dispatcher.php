<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    protected $table = 'dispatcher';

    protected $fillable = ['full_name', 'email', 'role'];
}
