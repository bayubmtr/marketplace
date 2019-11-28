<?php
namespace App;
use Eloquent;
class User_profile extends Eloquent {
    protected $fillable = [
    'user_id', 'first_name', 'last_name', 'phone_number','gender','date_of_birth', 'avatar'
                        ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function order(){
        return $this->hasMany('App\Order', 'user_id', 'user_id');
    }
    public function getFullNameAttribute(){
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    public function getAvatarAttribute($value){
        if(!$value){
            return asset('storage/icon/user-avatar.png');
        }
        return asset('storage/user/avatar/' . $value);
    }
}
