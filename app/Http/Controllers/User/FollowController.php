<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Store_follower;
use App\Store_statistic;

class FollowController extends UserController
{
    public function index(){
        $user_id = auth()->user()->id;
        $wishlist = Store_follower::with('store.store_statistic', 'store.user:id,last_activity')->where('user_id', $user_id)->get();
        
        return ok($wishlist);
    }
    public function show(Request $request, $id){
        $user_id = auth()->user()->id;
        $wishlist = Store_follower::where('user_id', $user_id)->where('store_id', $id)->first();
        return ok($wishlist);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $result = false;
        $data = Store_follower::where('user_id', $user_id)->where('store_id', request('store_id'))->first();
        $statistic = Store_statistic::find(request('store_id'));
        if($data){
            $data->delete();
            $result = false;
            if($statistic->follower_count > 0){
                $statistic->follower_count = $statistic->follower_count-1;
                $statistic->save();
            }
        }else{
            $following = new Store_follower;
            $following->user_id = $user_id;
            $following->store_id = request('store_id');
            $following->save();
            $result = true; 
            $statistic->follower_count = $statistic->follower_count+1;
            $statistic->save();
        }
        return ok(['following' => $result]);
    }
}