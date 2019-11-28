<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address_city extends Model
{
    protected $fillable = [
        'name', 'province_id', 'type'
    ];
    public $timestamps = false;
    public function address_province(){
        return $this->belongsTo('App\Address_province', 'province_id', 'id');
    }
    public function address_district(){
        return $this->hasMany('App\Address_district', 'city_id', 'id');
    }
}
