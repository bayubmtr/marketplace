<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_user_role extends Model
{
    //
    public $timestamps = false;
    public function user()
    {
        return $this->hasMany('App\Store', 'user_role_id', 'id');
    }
}
