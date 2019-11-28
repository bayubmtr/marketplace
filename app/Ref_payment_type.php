<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_payment_type extends Model
{
    //
    protected $fillable = [
        'type', 'description'
    ];
    public $timestamps = false;
    public function payment()
    {
        return $this->hasMany('App\Payment', 'payment_type_id', 'id');
    }
}
