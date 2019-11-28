<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_detail extends Model
{
    protected $fillable = [
        'order_id', 'store_id', 'sub_total', 'total_weight', 'meta'
    ];
    public $timestamps = false;
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function store(){
        return $this->belongsTo('App\Store');
    }
    public function order_item(){
        return $this->HasMany('App\Order_item');
    }
    public function order_shipment_detail(){
        return $this->HasOne('App\Order_shipment_detail');
    }
    public function order_status(){
        return $this->belongsTo('App\Ref_order_status', 'order_status_id', 'id');
    }
}
