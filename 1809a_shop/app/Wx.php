<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wx extends Model
{
    //
    protected $table = 'wx';
    public $timestamps = false;
    public $primaryKey = 'id';
}
