<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address_district extends Model
{
    protected $fillable = [
        'name', 'city_id'
    ];
    public $timestamps = false;
    public function address_city(){
        return $this->belongsTo('App\Address_city', 'city_id', 'id');
    }
    public function address_village(){
        return $this->hasMany('App\Address_village', 'district_id', 'id');
    }
}
