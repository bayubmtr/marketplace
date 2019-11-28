<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $casts = [
        'specification' => 'array'
    ];
    Protected $primaryKey = "id";
    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }
    public function category(){
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
    public function photos(){
        return $this->hasMany('App\Product_photo');
    }
    public function photo(){
        return $this->hasOne('App\Product_photo')->latest();
    }
    public function location(){
        return $this->store()->address();
    }
    public function product_status(){
        return $this->belongsTo('App\Ref_product_status', 'product_status_id', 'id');
    }
    public function review(){
        return $this->hasMany('App\Product_review');
    }
    public function product_statistic(){
        return $this->hasOne('App\Product_statistic', 'product_id', 'id');
    }
    public function discussion(){
        return $this->hasMany('App\Product_discussion');
    }
    public function cart(){
        return $this->hasMany('App\Shopping_cart');
    }
    public function order_item(){
        return $this->hasMany('App\Order_item');
    }
}
