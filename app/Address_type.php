<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Address_type extends Model
{
    protected $fillable = [
        'address_id', 'address_type_id'
    ];
    public $timestamps = false;
    public function address(){
        return $this->belongsTo('App\Address', 'address_id', 'id');
    }
    public function ref_address_type(){
        return $this->belongsTo('App\Ref_address_type', 'address_type_id', 'id');
    }
}
