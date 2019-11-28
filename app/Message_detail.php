<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Message_detail extends Model
{
    protected $fillable = [
        'message_id', 'recipient_id', 'message', 'is_read', 'created_at'
    ];
    const UPDATED_AT = null;
    public function receiver(){
        return $this->belongsTo('App\User_profile', 'receiver_id', 'user_id');
    }
}
