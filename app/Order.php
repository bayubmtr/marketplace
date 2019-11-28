<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    protected $fillable = [
        'id', 'user_id', 'expired_at', 'created_at'
    ];
    const UPDATED_AT = null;
    public function user(){
        return $this->belongsTo('App\User_profile', 'user_id', 'user_id');
    }
    public function order_detail(){
        return $this->hasMany('App\Order_detail');
    }
    public function order_promo(){
        return $this->hasMany('App\Order_promo');
    }
    public function order_shipment(){
        return $this->hasOne('App\Order_shipment');
    }
    public function order_payment()
    {
        return $this->hasOne('App\Order_payment');
    }
}
