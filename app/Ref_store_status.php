<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_store_status extends Model
{
    //
    public $timestamps = false;
    public function store()
    {
        return $this->hasMany('App\Store', 'store_status_id', 'id');
    }
}
