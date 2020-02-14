<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment_Standards extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'assessments_standards';
    public $timestamps = false;
}
