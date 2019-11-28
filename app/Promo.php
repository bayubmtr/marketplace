<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Promo extends Model{
    public function promo_term(){
        return $this->hasMany('App\Promo_term');
    }
    public function getImageAttribute($value){
        return asset('storage/website/promo/' . $value);
    }
}
