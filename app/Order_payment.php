<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_payment extends Model
{
    public function order(){
        return $this->belongsTo('App\Order');
    }
    public function payment_type(){
        return $this->belongsTo('App\Ref_payment_type', 'payment_type_id', 'id');
    }
    public function payment_status(){
        return $this->belongsTo('App\Ref_payment_status', 'payment_status_id', 'id');
    }
    public function getIdAttribute(){
        return ('INV-'.$this->attributes['id']);
    }
}
