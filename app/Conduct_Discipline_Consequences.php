<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conduct_Discipline_Consequences extends Model
{
    //
    protected $primaryKey = 'Consquence_ID';

    protected $table = 'conduct_discipline_consequences';
    public $timestamps = false;
}
