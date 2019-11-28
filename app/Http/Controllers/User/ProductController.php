<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Product;
use App\Product_photo;
use App\Http\Traits\user\ProductTrait;
use DB;
use Validator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use \stdClass;

class ProductController extends UserController
{
	use ProductTrait;
	public function index(Request $request){
		if(!request('pageLength')){
			$request->request->add(['pageLength' => 12]);
		  }
		$products = $this->filterProductTrait(request()->all());
		if($products){
			return $products;
		}else{
			return response()->json(['status' => '500', 'msg' => 'something errors !']);
		}
	}
    public function show($id){
		$data = $this->getProductTrait($id);
		return ok($data);
	}
	
}
