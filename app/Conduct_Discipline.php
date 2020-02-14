<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conduct_Discipline extends Model
{
    //
    protected $primaryKey = 'ID';

    protected $table = 'conduct_discipline';
    public $timestamps = false;

    public function conduct_discipline_consequences()
    {
        return $this->hasMany('App\Conduct_Discipline');
    }
}
