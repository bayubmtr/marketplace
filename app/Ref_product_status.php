<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ref_product_status extends Model
{
    //
    public $timestamps = false;
    public function product()
    {
        return $this->hasMany('App\Product', 'product_status_id', 'id');
    }
}
