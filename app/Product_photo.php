<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Product_photo extends Model{
    protected $fillable = [
        'product_id', 'high', 'medium', 'small'
    ];
    public function product(){
        return $this->belongsTo('App\Product_photos');
    }
    public function getMediumAttribute($value){
        return asset('storage/product/200x200/' . $value);
    }
    public function getSmallAttribute($value){
        return asset('storage/product/50x50/' . $value);
    }
    public function getHighAttribute($value){
        return asset('storage/product/1000x1000/' . $value);
    }
}
