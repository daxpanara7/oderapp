<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodeMaintain extends Model
{
    protected $table = 'code_maintain';

    protected $fillable = ['token_internal'];
}
