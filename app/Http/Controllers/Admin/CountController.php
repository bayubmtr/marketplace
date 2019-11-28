<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Order_detail;
use App\Product;
use App\Store;

class CountController extends AdminController
{
	public function index(){
    $user = User::count();
    $Order = Order_detail::count();
    $product = Product::count();
    $store = Store::count();
		return ok(['userCount' => $user, 'orderCount' => $Order, 'productCount' => $product, 'storeCount' => $store]);
	}
}
