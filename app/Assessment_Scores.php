<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment_Scores extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'assessments_scores';
    public $timestamps = false;
}
