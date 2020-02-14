<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_Parent extends Model
{
    //
    protected $table = 'users_parent';
    public $timestamps = false;

    public function parent_students()
    {
        return $this->hasMany('App\Parent_Student','id','parent_id');
    }
}
