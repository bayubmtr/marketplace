<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_shipment_status extends Model
{
    //
    public $timestamps = false;
    public function shipment()
    {
        return $this->hasMany('App\Shipment', 'shipment_status_id', 'id');
    }
}
