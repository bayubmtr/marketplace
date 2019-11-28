<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_shipment extends Model
{
    protected $fillable = [
         'order_id', 'village_id', 'expired_at', 'recipient', 'phone_number', 'address_detail'
    ];
    public $timestamps = false;
    public function address_village(){
        return $this->belongsTo('App\Address_village', 'village_id', 'id');
    }
    public function order_detail(){
        return $this->belongsTo('App\Order_detail');
    }
}
