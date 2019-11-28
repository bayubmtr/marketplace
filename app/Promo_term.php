<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Promo_term extends Model{
    public function promo(){
        return $this->belongsTo('App\Promo');
    }
    public function promo_variable(){
        return $this->belongsTo('App\Promo_variable', 'promo_variable_id', 'id');
    }
}
