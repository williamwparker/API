<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment_Status extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'assessments_status';
    public $timestamps = false;
}
