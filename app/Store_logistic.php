<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Store_logistic extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'logistic_id';
    public function store(){
        return $this->belongsTo('App\Store');
    }
    public function logistic(){
        return $this->belongsTo('App\Logistic');
    }
}
