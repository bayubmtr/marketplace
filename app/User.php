<?php
namespace App;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'password', 'email', 'user_status_id', 'user_role_id'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function profile(){
        return $this->hasOne('App\User_profile');
    }
    public function wallet(){
        return $this->hasOne('App\User_wallet');
    }
    public function bank(){
        return $this->hasMany('App\User_bank');
    }
    public function withdraw(){
        return $this->hasMany('App\User_withdraw');
    }
    public function income(){
        return $this->hasMany('App\User_income');
    }
    public function address(){
        return $this->hasMany('App\Address');
    }
    public function store(){
        return $this->hasOne('App\Store');
    }
    public function user_status(){
        return $this->belongsTo('App\Ref_user_status', 'user_status_id', 'id');
    }
    public function user_role(){
        return $this->belongsTo('App\Ref_user_role', 'user_role_id', 'id');
    }
    public function message(){
        return $this->hasMany('App\Message_detail', 'recipient_id', 'id');
    }
    public function following(){
        return $this->hasMany('App\Store_follower');
    }
    public function order(){
        return $this->hasMany('App\Order');
    }
    public function cart(){
        return $this->hasMany('App\Shopping_cart');
    }
    public function discussion(){
        return $this->hasMany('App\Product_discussion');
    }
   public function getJWTIdentifier(){
       return $this->getKey();
   }
   public function getJWTCustomClaims(){
       return [];
   }
}
