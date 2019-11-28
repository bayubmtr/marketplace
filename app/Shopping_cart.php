<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Shopping_cart extends Model{
    protected $fillable = [
        'product_id', 'is_checkout', 'promo_code', 'user_id', 'note', 'logistic_id', 'logistic_service', 'shipment_cost', 'quantity', 'address_id', 'created_at', 'update_at'
    ];
    public function buyer(){
        return $this->belongsTo('App\User');
    }
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function address(){
        return $this->belongsTo('App\Address');
    }
    public function logistic(){
        return $this->belongsTo('App\Logistic');
    }
}
