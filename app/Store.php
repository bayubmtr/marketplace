<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Store extends Model
{
    protected $fillable = [
        'user_id', 'store_url', 'name', 'slogan', 'description', 'address_id', 'image', 'product_count', 'review_count', 'review_avg', 'last_login', 'created_at', 'updated_at'
    ];
    public function product(){
        return $this->hasMany('App\Product');
    }
    public function user_profile(){
        return $this->belongsTo('App\User_profile', 'user_id', 'user_id');
    }
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function slideshow(){
        return $this->hasMany('App\Store_slideshow');
    }
    public function review(){
        return $this->hasOne('App\Store_review');
    }
    public function store_statistic(){
        return $this->hasOne('App\Store_statistic');
    }
    public function follower(){
        return $this->hasMany('App\Store_follower');
    }
    public function store_logistic(){
        return $this->hasMany('App\Store_logistic');
    }
    public function cart(){
        return $this->hasMany('App\Shopping_cart');
    }
    public function order_detail(){
        return $this->hasMany('App\Order_detail');
    }
    public function address(){
        return $this->hasMany('App\Address', 'user_id', 'user_id');
    }
    public function store_address(){
        return $this->address()->with('address_village.address_district', 'address_type')->whereHas('address_type',function($q){
            $q->where('address_type_id',2);
            });
    }
    public function getLogoAttribute($value){
        if(!$value){
            return asset('storage/icon/store-logo.png');
        }
        return asset('storage/store/logo/' . $value);
    }
    public function getBackgroundAttribute($value){
        if(!$value){
            return null;
        }
        return asset('storage/store/background/' . $value);
    }
    public function message(){
        return $this->HasMany('App\Message');
    }
}
