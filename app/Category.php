<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    public $timestamps = false;
    public function childs(){
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
    public function parent(){
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }
    public function product(){
        return $this->hasMany('App\Product');
    }
}
