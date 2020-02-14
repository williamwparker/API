<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Books_Libraries extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'books_libraries';
    public $timestamps = false;
}
