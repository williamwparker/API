<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment_Questions extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'assessments_questions';
    public $timestamps = false;
}
