<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Markup extends Model
{
    protected $table = 'markups';
	
	protected $fillable = ['type', 'value'];
}