<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    public $timestamps = false;
    public function store_logistic()
    {
        return $this->hasMany('App\Store_logistic', 'logistic_id', 'id');
    }
    public function getLogoAttribute($value){
        return asset('storage/icon/logistic/' . $value);
    }
    public function getLogoNameAttribute($value){
        return $this->logo;
    }
}
