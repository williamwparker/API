<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Group extends Model
{

    protected $primaryKey = 'ID';

    protected $table = 'students_groups';
    public $timestamps = false;
}
