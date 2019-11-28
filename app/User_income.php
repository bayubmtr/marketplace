<?php
namespace App;
use Eloquent;
class User_income extends Eloquent {
    const UPDATED_AT = null;
    public function user(){
        return $this->belongsTo('App\User');
    }
}
