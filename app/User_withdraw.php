<?php
namespace App;
use Eloquent;
class User_withdraw extends Eloquent {
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function user_profile(){
        return $this->belongsTo('App\User_profile', 'user_id', 'user_id');
    }
    public function user_bank(){
        return $this->belongsTo('App\User_bank', 'user_bank_id', 'id');
    }
    public function withdraw_status(){
        return $this->belongsTo('App\Ref_withdraw_status');
    }
}
