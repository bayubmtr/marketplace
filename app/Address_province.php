<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address_province extends Model
{
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;
    public function address_city(){
        return $this->hasMany('App\Address_city', 'province_id', 'id');
    }
}