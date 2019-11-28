<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Store_slideshow extends Model{
    public $timestamps = false;
    public function store(){
        return $this->belongsTo('App\Store');
    }
    public function getSlideshowAttribute($value){
        return asset('storage/store/slideshow/' . $value);
    }
}
