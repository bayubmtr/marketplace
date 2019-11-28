<?php
namespace App;
use Eloquent;
class User_bank extends Eloquent {
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function user_withdraw(){
        return $this->hasMany('App\User_withdraw', 'user_bank_id', 'id');
    }
}
