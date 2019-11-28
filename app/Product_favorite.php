<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Product_favorite extends Model{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'product_id'
    ];
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function user(){
        return $this->belongsTo('App\User_profile', 'user_id', 'id');
    }
}
