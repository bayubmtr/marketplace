<?php
namespace App\Http\Traits\user;
use App\Product;
use App\Product_statistic;
use App\Product_favorite;
use App\Category;
use DB;
use Request;
use Cookie;
trait ProductTrait {

  public function getProductTrait($id){
    $root = Request::root();
    $user_id = null;
    if(isset(auth()->user()->id)){
      $user_id = auth()->user()->id;
    }
    $product = Product::where('products.id', $id)->with('category.parent.parent', 'store:id,user_id,store_url,name,logo,created_at', 'photos:id,product_id,small,medium,high', 'product_statistic', 'review.user', 'store.store_logistic.logistic', 'store.store_statistic', 'store.user:id,last_activity')
    ->where('stores.deleted_at', null)
    ->join('stores', 'products.store_id', '=', 'stores.id')
    ->leftjoin('store_followers', 'stores.id', '=', 'store_followers.store_id')
    ->leftjoin('product_statistics', 'products.id', '=', 'product_statistics.product_id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->join('addresses', 'stores.user_id', '=', 'addresses.user_id')
    ->join('address_types', function($join){
      $join->on('address_types.address_id', '=', 'addresses.id');
      $join->on('address_type_id', '=', DB::raw("'2'"));
    })
    ->join('address_villages', 'addresses.village_id', '=', 'address_villages.id')
    ->join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
    ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
    ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')->select('products.id as id', 
    'products.id',
    'products.store_id',
    'products.name as name',
    'products.price as price',
    'products.discount as discount',
    'products.condition',
    'products.category_id',
    'products.description',
    'products.updated_at',
    'products.stock as stock',
    'product_statistics.view_count as view',
    'product_statistics.sold_count',
    'products.weight',
    'products.specification',
    'product_statistics.favorite_count as favorite',
    'categories.name as category',
    DB::raw("CONCAT(address_cities.name,' - ', address_provinces.name) AS location"),
    DB::raw("count(store_followers.store_id) as follower_count")
    )->first();
    $wishlist = Product_favorite::where('user_id', $user_id)->where('product_id', $product->id)->first();
    if($wishlist){
      $product->wishlist = true;
    }else{
      $product->wishlist = false;
    }
  if($product){
    $this->setView($id);
    return $product;
  }
  }
  public function createProductStatistic($id){
    $sts = Product_statistic::firstOrCreate(['product_id' => $id]);
  }
  private function setView($id){
    $view = Product_statistic::where('product_id', $id)->first();
    $view->view_count = $view->view_count+1;
    $view->save();
  }
  public function filterProductTrait(){
    $root = Request::root();
    $product = Product::with('photo:product_id,medium')->where('product_status_id', 1)
    ->leftjoin('product_statistics', 'products.id', '=', 'product_statistics.product_id')
    ->join('stores', 'products.store_id', '=', 'stores.id')
    ->join('ref_product_statuses', 'products.product_status_id', '=', 'ref_product_statuses.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->leftjoin('categories as cat2', 'categories.parent_id', '=', 'cat2.id')
    ->leftjoin('categories as cat1', 'cat2.parent_id', '=', 'cat1.id')
    ->join('addresses', 'stores.user_id', '=', 'addresses.user_id')
    ->join('address_types', function($join){
      $join->on('address_types.address_id', '=', 'addresses.id');
      $join->on('address_type_id', '=', DB::raw("'2'"));
    })
    ->join('address_villages', 'addresses.village_id', '=', 'address_villages.id')
    ->join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
    ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
    ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id');
    if(request()->has('id')){
      $product->wherein('products.id',request('id'));
    }
    if(request()->has('name')){
      $product->where('products.name','like','%'.request('name').'%');
    }
    if(request()->has('search')){
      $product->where('products.name','like','%'.request('search').'%');
    }
    if(request()->has('category_id')){
      $product->where('products.category_id', request('category_id'));
    }
    if(request()->has('brand_id')){
      $product->where('products.brand_id', request('brand_id'));
    }
    if(request()->has('store_id')){
      $product->where('stores.id',request('store_id'));
    }
    if(request()->has('store_url')){
      $product->where('stores.store_url','like','%'.request('store_url').'%');
    }
    if(request()->has('store_name')){
      $product->where('stores.name','like','%'.request('store_name').'%');
    }
    if(request()->has('store_review')){
      $product->where('stores.review_avg', '>=', request('store_review'));
    }
    if(request()->has('minPrice') && request()->has('maxPrice')){
      $product->whereBetween('products.price', array(request('minPrice'), request('maxPrice')));
    }
    elseif(request()->has('minPrice')){
      $product->where('products.price', '>=', request('maxPrice'));
      }
    elseif(request()->has('maxPrice')){
      $product->where('products.price', '<=', request('maxPrice'));
      }
    if(request()->has('discount')){
      if((request('discount') > 0)){
        $discount = request('discount');
      }else{
        $discount = 1;
      }
      $product->where('products.discount', '>=', $discount);
    }
    if(request()->has('cat')){
      $product->where(function($query) {
        $query->where('products.category_id', request('cat'))
        ->orwhere('cat2.id', request('cat'))
        ->orwhere('cat1.id', request('cat'));
      });
    }
    if(request()->has('rate')){
      $product->where('product_statistics.review_avg', '>=', request('rate'));
    }
    if(request()->has('location')){
      $product->where('address_provinces.id', '=', request('location'));
    }
    if(request()->has('sold')){
      $product->where('products.sold_count', '>=', request('sold'));
    }
    if(request()->has('villages')){
      $product->where('address_villages.name', request('villages'));
    }
    if(request()->has('districts')){
      $product->where('address_districts.name', request('districts'));
    }
    if(request()->has('cities')){
      $product->where('address_cities.name', request('cities'));
    }
    if(request()->has('provinces')){
      $product->where('address_provinces.name', request('provinces'));
    }
    if(request('sortBy') == 'price'){
      $product->orderBy('products.price', request('order'));
    }
    elseif(request('sortBy') == 'sales'){
      $product->orderBy('product_statistics.sold_count', 'DESC');
    }
    elseif(request('sortBy') == 'pop'){
      $product->orderBy('view', 'DESC');
    }
    elseif(request('sortBy') == 'ctime'){
      $product->orderBy('products.created_at', 'DESC');
    }
    elseif(request('sortBy') == 'random'){
      $product->inRandomOrder();
    }
    $product->select('products.id as id', 
            'products.name as name',
            'products.price as price',
            'products.discount as discount',
            'products.name as name',
            'product_statistics.view_count as view',
            'product_statistics.sold_count',
            'products.weight',
            'products.stock',
            'product_statistics.review_avg as review_avg',
            'product_statistics.review_count as review_count',
            'product_statistics.favorite_count as favorite',
            'stores.name as store_name',
            'stores.store_url as store_url',
            'categories.name as category',
            'ref_product_statuses.name as status',
            DB::raw("CONCAT(address_cities.name,' - ', address_provinces.name) AS location")
            )->where('products.deleted_at', null);
    if($product){
      return $product->paginate(request('pageLength'));
    }
  }
}