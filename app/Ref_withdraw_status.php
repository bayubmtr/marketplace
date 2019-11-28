<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_withdraw_status extends Model
{
    public function user_withdraw()
    {
        return $this->hasMany('App\user_withdraw');
    }
}
