<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment_Settings extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'assessments_settings';
    public $timestamps = false;
}
