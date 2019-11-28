<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Store;
use App\Store_follower;
use App\Store_logistic;
use DB;

class StoreController extends UserController
{
    public function show($storeUrl){
        $store = Store::with('store_statistic', 'slideshow')->withCount('follower')->where('store_url', $storeUrl)->where('deleted_at', null)->get()->first();
        if (!$store){
        return response()->json(['status' => '404', 'message' => 'Store not found !']);
        }
        $is_follow = false;
        if(isset(auth()->user()->id)){
            $is_follow = Store_follower::where('user_id', auth()->user()->id)->where('store_id', $store->id)->first();
        }
        if($is_follow){
            $store->following = true;
        }else{
            $store->following = false;
        }
        return ok($store);
    }
    public function getAllProducts($storeurl){
        $store = Store::where('store_url', $storeurl)->where('deleted_at', null)->get()->first();
        if (!$store){
            return response()->json(['status' => '404', 'message' => 'Store not found !']);
        }
        else {
        $category = Store_product_category::select('category_name', 'id')->where('store_id', $store->id)->get();
		$products = Product::with('store_product_category.category_list')->where('store_id', $store->id);

        if(request()->has('key'))
            $products->where('name','like','%'.request('key').'%');
        
        if(request()->has('cat'))
            $products->whereHas('store_product_category.category_list',function($q){
            $q->where('category_name','like','%'.request('cat').'%');
            });

        if(request()->has('rating'))
            $products->where('review_avg','>=',request('rating'));

        if(request()->has('min_price') && request()->has('max_price'))
            $products->whereBetween('price', array(request('min_price'), request('max_price')));
        
        if(request('sortBy') == 'pop')    
            $products->orderBy('sold_count','desc')->orderBy('favorite_count','desc');
        if(request('sortBy') == 'ctime')    
            $products->orderBy('created_at','desc');
        if(request('sortBy') == 'sales')    
            $products->orderBy('sold_count','desc');
            
        if(request()->has('order'))
            $products->orderBy('price',request('order'));

        return response()->json(['products' => $products->paginate(request('pageLength')), 'store' => $store, 'category' => $category]);
        }
    }
}