<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counter extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'number', 'name', 'deleted'];
}
