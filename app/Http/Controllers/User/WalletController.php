<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\User_wallet;
use App\Http\Traits\user\ProductTrait;

class WalletController extends UserController
{
    use ProductTrait;
    public function __construct()
    {
        if(!isset(auth()->user()->wallet)){
            $new = new User_wallet;
            $new->user_id = auth()->user()->id;
            $new->balance = 0;
            $new->save();
        }
    }
    public function index(){
        $data = auth()->user()->wallet;
        return ok($data);
    }
    public function show($id){
        $data = auth()->user()->wallet;
        return ok($data);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $data = Product_favorite::firstOrCreate(['user_id' => $user_id, 'product_id' => request('product_id')]);
        $add = Product_statistic::where('product_id', request('product_id'))->first();
        $add->favorite_count = $add->favorite_count+1;
        $add->save();
        return ok($data);
    }
    public function destroy($id){
        $user_id = auth()->user()->id;
        $data = Product_favorite::where('product_id', $id)->where('user_id', $user_id);
        
        return ok($data);
    }
    public function withdraw($amount){
        $user_id = auth()->user()->id;
        $data = User_wallet::where('user_id', $user_id)->first();
        $data->balance = $data->balance-$amount;
        $data->save();
        return $data;
    }
}