<?php

namespace App\Http\Controllers\User\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Store_statistic;
use App\Product_statistic;
use App\Product_photo;
use App\Order_item;
use App\Http\Traits\user\ProductTrait;
use DB;
use Validator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use \stdClass;


class ProductController extends Controller
{
	public function index(Request $request){
		$store_id = auth()->user()->store->id;
		$request->request->add(['store_id' => $store_id]);
		$products = $this->getFilterProduct(request()->all());
		if($products){
			return ok($products);
		}else{
			return response()->json(['status' => '500', 'msg' => 'something errors !']);
		}
	}
    public function show($id){
		$data = Product::with('photos')->select('id', 'name', 'price', 'condition', 'description', 'weight', 'stock', 'specification')->where('id', $id)->first();
		return ok($data);
	}
	public function update(Request $request, $id){
		if(request('type') == 'update'){
			$update = $this->updateProduct(request()->all(), $id);
			return $update;
		}else{
			$update = Product::find($id);
			if($update){
				if(request('type') == 'sell'){
					$update->product_status_id = 1;
				}elseif(request('type') == 'archive'){
					$update->product_status_id = 2;
				}
				$update->save();
				return ok(['message' => 'Success update product !']);
			}
			return not_found('Not Found !', ['id' => 'Product Id no Found !']);
		}
	}
	public function destroy($id){
		$check = Order_item::where('product_id', $id)->first();
		$delete = Product::find($id);
		if(!$check){
			$delete->delete();
		}else{
			$delete->product_status_id = 3;
			$delete->save();
		}
		return ok(['message' => 'Success delete product !']);
	}
	private function updateProduct($id){
		$store_id = auth()->user()->store->id;
		$validation = Validator::make(request()->all(),[
			'name' => 'required|min:1|max:100',
            'price' => 'required',
			'condition' => 'required|in:New,Used',
			'description' => 'required|min:3',
			'weight' => 'required|integer',
			'stock' => 'required|integer',
		]);
		if($validation->fails())
			return response()->json(['message' => $validation->messages()],422);
		$product = Product::where('store_id', $store_id)->where('id', $id)->first();
		$product->name = request('name');
		$product->price = request('price');
		$product->condition = request('condition');
		$product->description = request('description');
		$product->weight = request('weight');
		$product->stock = request('stock');
		$product->save();
		
		return ok($product);
	}
	public function store(Request $request)
    {
		$store_id = auth()->user()->store->id;
		$validation = Validator::make(request()->all(),[
			'name' => 'required|min:10|max:100',
			'productPhotos' => 'required',
            'price' => 'required',
			'condition' => 'required|in:New,Used',
			'description' => 'required|min:30',
			'weight' => 'required|integer',
			'stock' => 'required|integer',
		]);
		if($validation->fails())
			return response()->json(['message' => $validation->messages()],422);
		$specification = new stdClass();
		foreach (request('specification') as $key => $value)
		{
			if($value['name'] != null){
				$specification->$key = $value;
			}
		}
		$product = new Product;
		$product->store_id = $store_id;
		$product->category_id = request('category_id');
		$product->name = request('name');
		$product->price = request('price');
		$product->condition = request('condition');
		$product->description = request('description');
		$product->weight = request('weight');
		$product->stock = request('stock');
		$product->product_status_id = 1;
		$product->specification = $specification;
		$product->save();
		
		foreach (request('productPhotos') as $key => $image) {
			$name = Uuid::uuid1().'.'.substr($image,11,4);
			$high = \Image::make($image);
			$high->resize(1000, 1000);
			$high->save(public_path('storage/product/1000x1000/').$name);
			$medium = \Image::make($image);
			$medium->resize(150, 150);
			$medium->save(public_path('storage/product/200x200/').$name);
			$small = \Image::make($image);
			$small->resize(50, 50);
			$small->save(public_path('storage/product/50x50/').$name);


		$product_photo = new Product_photo;
		$product_photo->product_id = $product->id;
		$product_photo->high = $name;
		$product_photo->medium = $name;
		$product_photo->small = $name;
		$product_photo->save();
		}
		$statistic = Store_statistic::where('store_id', $store_id)->first();
		if(!$statistic){
			$add_statistic = new Store_statistic;
			$add_statistic->store_id = $store_id;
			$add_statistic->product_count = 1;
			$add_statistic->save();
		}else{
			$statistic->product_count = $statistic->product_count+1;
			$statistic->save();
		}
		$product_statistic = new Product_statistic;
		$product_statistic->product_id = $product->id;
		$product_statistic->save();
		return ok($product);
    }
	private function getFilterProduct(){
		$product = Product::whereNotIn('product_status_id', [3,11])
		->where('products.store_id', request('store_id'))
		->leftjoin('product_statistics', 'products.id', '=', 'product_statistics.product_id')
		->join('ref_product_statuses', 'products.product_status_id', '=', 'ref_product_statuses.id')
		->join('categories', 'products.category_id', '=', 'categories.id');
		if(request()->has('name')){
		  $product->where('products.name','like','%'.request('name').'%');
		}
		if(request()->has('status') && request('status') != 'all'){
			$product->where('ref_product_statuses.name',request('status'));
		  }
		if(request()->has('category_id')){
		  $product->where('products.category_id', request('category_id'));
		}
		if(request()->has('store_review')){
		  $product->where('stores.review_avg', '>=', request('store_review'));
		}
		if(request()->has('min_price') && request()->has('max_price')){
		  $product->whereBetween('products.price', array(request('min_price'), request('max_price')));
		}
		  elseif(request()->has('min_price')){
			$product->where('products.price', '>=', request('min_price'));
		  }
		  elseif(request()->has('max_price')){
			$product->where('products.price', '<=', request('max_price'));
		  }
		if(request()->has('discount')){
		  if((request('discount') > 0)){
			$discount = request('discount');
		  }else{
			$discount = 1;
		  }
		  $product->where('products.discount', '>=', $discount);
		}
		if(request()->has('rating')){
		  $product->where('products.review_avg', '>=', request('rating'));
		}
		if(request()->has('sold')){
		  $product->where('products.sold_count', '>=', request('sold'));
		}
		if(request('sortBy') == 'price'){
		  $product->orderBy('products.price', request('order'));
		}
		elseif(request('sortBy') == 'sold'){
		  $product->orderBy('products.sold_count', 'ASC');
		}
		elseif(request('sortBy') == 'favorite'){
		  $product->orderBy('products.favorite_count', 'ASC');
		}
		elseif(request('sortBy') == 'review'){
		  $product->orderBy('products.review_count', 'ASC');
		}
		elseif(request('sortBy') == 'utime'){
		  $product->orderBy('products.update_at', 'ASC');
		}
		$product->select('products.id as id', 
				'products.name as name',
				'products.price as price',
				'products.discount as discount',
				'products.name as name',
				'product_statistics.view_count as view',
				'product_statistics.sold_count',
				'products.stock',
				'product_statistics.review_avg as review_avg',
				'product_statistics.review_count as review_count',
				'product_statistics.favorite_count as favorite',
				'categories.name as category',
				'ref_product_statuses.name as status'
	  )->groupBy('products.id');
		if($product){
		  return $product->paginate(request('pageLength'));
		}
	  }
}
