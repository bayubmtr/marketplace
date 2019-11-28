<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends AdminController
{
	public function index(){
		$products = Product::with('store:id,name,store_url', 'category', 'product_status', 'product_statistic');
        if(request()->has('name'))
            $products->where('name','like','%'.request('name').'%');
        if(request()->has('category'))
            $products = Product::with('category');
            $products->whereHas('category',function($q){
                $q->where('name','like','%'.request('category').'%');
            });
        if(request()->has('store'))
            $products = Product::with('store');
            $products->whereHas('store',function($q){
                $q->where('name','like','%'.request('store').'%');
            });
        if(request()->has('status'))
        $products->where('product_status_id', request('status'));

        if(request('sortBy') == 'store')
        $products->with(['store' => function ($q) {
        $q->orderBy(request('sortBy'), request('order'));
        }]);
            else
      $products->orderBy(request('sortBy'),request('order'));

		return $products->paginate(request('pageLength'));
	}
    public function destroy($id){
        $destroy = Product::find($id);
        $destroy->product_status_id = 3;
        $destroy->save();
        return ok(['message' => 'Berhasil Menghapus Produk !']);
    }
    public function update(Request $request, $id){
        if(request('type') == 'banned'){
            $destroy = Product::find($id);
            $destroy->product_status_id = 11;
            $destroy->save();
            return ok(['message' => 'Produk Berhasil Di Banned !']);
        }elseif(request('type') == 'active'){
            $destroy = Product::find($id);
            $destroy->product_status_id = 1;
            $destroy->save();
            return ok(['message' => 'Produk Berhasil Di Aktifkan !']);
        }
    }
}
