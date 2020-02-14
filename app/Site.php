<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //

    protected $hidden = array('password', 'token');

    public $timestamps = false;
}
