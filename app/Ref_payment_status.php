<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_payment_status extends Model
{
    //
    public $timestamps = false;
    public function payment()
    {
        return $this->hasMany('App\Payment', 'payment_status_id', 'id');
    }
}
