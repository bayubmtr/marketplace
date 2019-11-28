<?php
namespace App;
use Eloquent;
class User_wallet extends Eloquent {
    public $timestamps = false;
    public function user(){
        return $this->belongsTo('App\User');
    }
}
