<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_shipment_detail extends Model{
    protected $fillable = [
        'order_detail_id', 'logistic_id', 'logistic_service', 'shipment_cost', 'shipment_tracking_number', 'sent_at', 'delivery_at'
    ];
    public $timestamps = false;
    public function order_detail(){
        return $this->belongsTo('App\Order_detail');
    }
    public function logistic(){
        return $this->belongsTo('App\Logistic');
    }
}
