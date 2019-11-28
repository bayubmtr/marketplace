<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function address_village(){
        return $this->belongsTo('App\Address_village', 'village_id', 'id');
    }
    public function address_type(){
        return $this->hasMany('App\Address_type');
    }
    public function shopping_cart(){
        return $this->hasMany('App\Shopping_cart', 'address_id', 'id');
    }
}
