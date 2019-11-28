<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Product_favorite;
use App\Product;
use App\Product_statistic;
use App\Http\Traits\user\ProductTrait;

class WishlistController extends UserController
{
    use ProductTrait;
    public function index(Request $request){
        $user_id = auth()->user()->id;
        $wishlist = Product_favorite::where('user_id', $user_id)->pluck('product_id');
        $request->request->add(['id' => $wishlist]);
        $data = $this->filterProductTrait(request()->all());
        return ok($data);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $data = Product_favorite::firstOrCreate(['user_id' => $user_id, 'product_id' => request('product_id')]);
        $this->createProductStatistic(request('product_id'));
        $add = Product_statistic::where('product_id', request('product_id'))->first();
        $add->favorite_count = $add->favorite_count+1;
        $add->save();
        return ok($data);
    }
    public function destroy($id){
        $user_id = auth()->user()->id;
        $data = Product_favorite::where('product_id', $id)->where('user_id', $user_id);
        $data->delete();
        $remove = Product_statistic::where('product_id', $id)->first();
        $remove->favorite_count = $remove->favorite_count-1;
        $remove->save();
        return ok($data);
    }
}