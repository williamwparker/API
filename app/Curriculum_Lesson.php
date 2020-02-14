<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum_Lesson extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'curriculum_lesson';
    public $timestamps = false;
}
