<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_item extends Model
{
    protected $fillable = [
        'order_detail_id', 'product_id', 'price', 'weight', 'discount', 'quantity'
    ];
    public $timestamps = false;
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function order_detail(){
        return $this->belongsTo('App\Order_detail');
    }
}
