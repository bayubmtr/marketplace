<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address_village extends Model
{
    protected $fillable = [
        'name', 'district_id', 'zip_code'
    ];
    public $timestamps = false;
    public function address_district(){
        return $this->belongsTo('App\Address_district', 'district_id', 'id');
    }
    public function address(){
        return $this->hasMany('App\Address', 'village_id', 'id');
    }
    public function order_shipment(){
        return $this->hasMany('App\Order_shipment', 'village_id', 'id');
    }
}
