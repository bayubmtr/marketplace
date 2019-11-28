<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Message extends Model
{
    protected $fillable = [
        'user_id', 'store_id', 'created_at'
    ];
    public function user(){
        return $this->belongsTo('App\User_profile', 'user_id', 'user_id');
    }
    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }
    public function message_detail(){
        return $this->HasMany('App\Message_detail');
    }
}
