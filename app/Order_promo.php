<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Order_promo extends Model
{
    public $timestamps = false;
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function promo()
    {
        return $this->belongsTo('App\Promo', 'promo_code', 'code');
    }
}
