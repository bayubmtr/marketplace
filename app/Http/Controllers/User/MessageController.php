<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Message;
use App\Message_detail;
use App\User_profile;
use App\Store;
use DB;

class MessageController extends UserController
{
    public function index(Request $request){
        $id = auth()->user()->id;
        $store_id = null;
        if(isset(auth()->user()->store->id)){
            $store_id = auth()->user()->store->id;
        }
        if(request('type') == 'search'){
            if(request()->has('store_id')){
                $data = Message::with('store:id,user_id,name,logo')
                ->where('user_id', $id)->where('store_id', request('store_id'))->first();
                if($data){
                    $data_detail = Message_detail::where('message_id', $data->id)->latest()->take(10)->get()->toArray();
                    $data->message_detail = array_reverse($data_detail);
                }
            }elseif(request()->has('user_id')){
                $data = Message::with('user:id,user_id,first_name,avatar,last_name')
                ->where('user_id', request('user_id'))->where('store_id', $store_id)->first();
               if($data){
                    $data_detail = Message_detail::where('message_id', $data->id)->latest()->take(10)->get()->toArray();
                    $data->message_detail = array_reverse($data_detail);
               }
            }
            if($data){
                $read = Message_detail::where('message_id', $data->id)->where('is_read', 0)->where('recipient_id', $id)->orwhere('recipient_id', $store_id)->update(['is_read' => 1]);
            }
        }else{
            $data = Message::with('message_detail')->where('user_id', $id)->orWhere('store_id', $store_id)->orderBy('updated_at', 'DESC')->get();
            foreach ($data as $key => $value) {
                if($value->user_id == auth()->user()->id){
                    $uid = $value->store_id;
                    $user = Store::select('name', 'id', 'user_id', 'logo')->where('id', $uid)->first();
                    $value->user = $user->name;
                    $value->avatar = $user->logo;
                }else{
                    $uid = $value->user_id;
                    $user = User_profile::select('first_name', 'last_name', 'avatar')->where('user_id', $uid)->first();
                    $value->user = $user->first_name.' '.$user->last_name;
                    $value->avatar = $user->avatar;
                }
            }
        }
        return ok($data);
    }
    public function show($id){
        $uid = auth()->user()->id;
        $sid = null;
        if(isset(auth()->user()->store->id)){
            $sid = auth()->user()->store->id;
        }
        $read = Message_detail::where('message_id', $id)->where('is_read', 0)->where('recipient_id', $uid)->orwhere('recipient_id', $sid)->update(['is_read' => 1]);
        $messages = Message_detail::where('message_id', $id)->get();
		return ok($messages);
    }
    public function store(Request $request){
        $id = auth()->user()->id;
        $store_id = null;
        if(isset(auth()->user()->store->id)){
            $store_id = auth()->user()->store->id;
        }
        if(request()->has('store_id')){
            $message = new Message([
                'user_id' => $id,
                'store_id' => request('store_id')
            ]);
            $message->save();
            $message_detail = new Message_detail([
                'recipient_id' => request('store_id'),
                'message_id' => $message->id,
                'message' => request('message')
            ]);
            $message_detail->save();
        }elseif(request()->has('user_id')){
            $message = new Message([
                'user_id' => request('user_id'),
                'store_id' => $store_id
            ]);
            $message->save();
            $message_detail = new Message_detail([
                'recipient_id' => request('user_id'),
                'message_id' => $message->id,
                'message' => request('message')
            ]);
            $message_detail->save();
        }
        return response()->json(['message' => 'Message has been send !']);
    }
    public function update(Request $request, $message_id){
        $id = auth()->user()->id;
        $store_id = null;
        if(isset(auth()->user()->store->id)){
            $store_id = auth()->user()->store->id;
        }
        $recipient = Message::find($message_id);
        if($recipient->store_id == $store_id){
            $recipient_id = $recipient->user_id;
        }elseif($recipient->user_id == $id){
            $recipient_id = $recipient->store_id;
        }
        $message_detail = new Message_detail([
            'recipient_id' => $recipient_id,
            'message_id' => $message_id,
            'message' => request('message')
        ]);
        $message_detail->save();
        $recipient->touch();
        if($message_detail){
            return ok($message_detail);
        }else{
            return ok(['message' => 'Terjadi Masalah !']);
        }
    }
}