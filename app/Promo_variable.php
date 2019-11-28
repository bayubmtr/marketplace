<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Promo_variable extends Model{
    public function promo_term(){
        return $this->hasMany('App\Promo_term');
    }
}
