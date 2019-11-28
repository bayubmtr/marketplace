<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_user_status extends Model
{
    //
    public $timestamps = false;
    public function user()
    {
        return $this->hasMany('App\User', 'user_status_id', 'id');
    }
}
