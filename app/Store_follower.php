<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Store_follower extends Model{
    public $timestamps = false;
    protected $fillable = [
        'store_id', 'user_id'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function store(){
        return $this->belongsTo('App\Store');
    }
}
