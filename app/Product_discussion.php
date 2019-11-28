<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Product_discussion extends Model{
    protected $fillable = [
        'id', 'user_id', 'product_id', 'parent_id', 'discussion'
    ];
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function user() {
        return $this->belongsTo('App\User_profile', 'user_id', 'user_id');
    }
    public function reply(){
        return $this->hasMany('App\Product_discussion', 'parent_id', 'id');
    }
}
