<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

class NotificationController extends UserController
{
    public function index(){
        $id = auth()->user()->id;
        $sid = null;
        if(isset(auth()->user()->store->id)){
            $sid = auth()->user()->store->id;
        }
        $notifications = \App\User::where('id', $id)->select('id', 'email')->withCount(['cart'])->first();
        $new_message = \App\Message_detail::where(function($query) use($id, $sid){
            $query->where('recipient_id', $id);
            $query->orwhere('recipient_id', $sid);
        })->where('is_read', 0)->count();
        $new_order = \App\Order::with('order_detail')->where('user_id', $id);
        $new_order->whereHas('order_detail',function($q){
            $q->wherein('order_status_id', [1,2,3,4,5]);
            });
        $new_order_count = count($new_order->get());
        $new_order_detail = \App\Order_detail::with('order')->wherein('order_status_id', [1,2,3,4,5])->where('store_id', $sid)->count();
        $notifications->message_count = $new_message;
        $notifications->order_count =  $new_order_count+ $new_order_detail;
   
    return ok($notifications);
    }
}