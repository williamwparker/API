<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conduct_Log extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'conduct_log';
    public $timestamps = false;
}
