<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Group_Student extends Model
{
    protected $primaryKey = 'ID';

    protected $fillable = ['StaffId', 'Group_ID', "Student_ID"];

    protected $table = 'students_groups_students';
    public $timestamps = false;
}
