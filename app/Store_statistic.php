<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Store_statistic extends Model{
    protected $fillable = [
        'id', 'store_id', 'product_count', 'review_count', 'review_avg'
    ];
    public $timestamps = false;
    public function store(){
        return $this->belongsTo('App\Store');
    }
}
