<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Store;
use App\Logistic;
use App\Product;
use App\Shopping_cart;
use App\Address;
use DB;
use App\Order;
use App\Order_detail;
use App\Order_item;
use App\Payment;
use App\Shipment;
use App\Invoice;
use Uuid;
use Cart;
use Session;
use \stdClass;
use Steevenz\Rajaongkir;
use App\Http\Traits\user\CartTrait;

class CartController extends UserController
{
    use CartTrait;
    public function __construct(){

    }
    public function index(){
        $user_id = auth()->user()->id;
        if(request('type') == 'checkout'){
            $cart = Shopping_cart::with('product:id,name,price,weight,store_id,stock', 'product.photo:product_id,small', 'address.address_village.address_district.address_city.address_province', 'product.store:id,name,logo,store_url', 'product.store.store_logistic.logistic')->where('user_id', $user_id)->where('is_checkout', 1)->get()->groupBy('product.store_id')->toArray();
        }else{
            $cart = Shopping_cart::with('product:id,name,price,weight,store_id,stock', 'product.photo:product_id,small', 'product.store:id,name,logo,store_url', 'product.store.store_logistic.logistic')->where('user_id', $user_id)->get()->groupBy('product.store_id')->toArray();
        }
        if (!$cart) {
            return no_content();
        }
        return ok($cart);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $store_id = null;
        if(isset(auth()->user()->store->id)){
            $store_id = auth()->user()->store->id;
        }
        $primary_address = null;
        $address = Address::where('user_id', $user_id)->get()->first();
        if($address){
            $primary_address = $address->address_type->where('address_type_id', 1);
        }
        if(!$primary_address){
            $shippingAddress = null;
        }else{
            $shippingAddress = $address->id;
        }
        $product = Product::select('id', 'store_id', 'stock')->where('id', request('product_id'))->get()->first();
        if($product->stock < request('quantity')){
            return not_acceptable('Stock produk tidak mencukupi !', $errors = ["quantity" => "cart quantity can't more than product stock and must be more than or equal to 1"], $status = 406);
        }elseif($store_id == $product->store_id){
            return not_acceptable('Anda tidak dapat membeli produk dari toko anda sendiri', $errors = [], $status = 406);
        }else{
            $data = Shopping_cart::updateOrCreate(
                ['product_id' => request('product_id'), 'user_id' => $user_id],
                ['quantity' => request('quantity'), 'address_id' => $shippingAddress]
            );
            if($data){
                return ok($data);
            }else{
                return error($message = 'something errors !', $errors = [], $status = 500);
            }
        }
    }
    public function update(Request $request, $id){
        if(request()->has('note')){
            $data = $this->updateNote($id, request('note'));
            return ok($data);
        }
        if(request()->has('updateQuantity')){
            $data = $this->updateQuantity($id, request('updateQuantity'));
                return $data;
        }
        if(request('req_type') == 'checkout'){
            $cart_id = explode(',', $id);
            $data = $this->checkoutCart($cart_id);
            if($data){
                return no_content();
            }
        }
        if(request('req_type') == 'check_promo'){
            $data = \App\Promo::with('promo_term.promo_variable')->where('code', $id)->where('is_active', 1)->first();
            if(!$data){
                return ok(0);
            }
            $cart = $this->getSelectedCartTrait(auth()->user()->id);
            $subTotal = 0;
            $obtain = 0;
            foreach ($cart as $key => $value) {
                $subTotal = $this->getSubTotalTrait($value);
            }
            if($data->promo_type == 'discount'){
                if($subTotal >= $data->promo_term[0]->value){
                    $obtain = $subTotal*$data->obtain/100;
                    if($obtain > $data->max_obtain){
                        $obtain = $data->max_obtain;
                    }
                    $store = Shopping_cart::where('user_id', auth()->user()->id)->where('is_checkout', true)->update(['promo_code' => $id]);
                }
            }
            if($obtain){
                return ok($obtain);
            }else{
                return ok(0);
            }
        }
        elseif(request('req_type') == 'set_logistic'){
            $data = $this->setLogistic(request()->all());
            return ok($data);
        }elseif(request('req_type') == 'set_logistic_service'){
            $data = $this->setLogisticService(request()->all());
            return ok(['message' => "Success set logistic service"]);
        }elseif(request('req_type') == 'set_address'){
            $data = $this->setCartAddress(request()->all());
            return ok(['message' => "Success change address"]);
        }
    }
    public function destroy($id){
        $cart_id = explode(',', $id);
        $data = Shopping_cart::whereIn('id', $cart_id);
        $data->delete();
        if($data){
            return ok(['message' => 'shopping cart successfully deleted !']);
        }else{
            return error($message = 'something errors !', $errors = [], $status = 500);
        }
    }
    private function updateNote($id, $note){
        $cart = Shopping_cart::find($id, ['note']);
        $cart->note = $note;
        $cart->save();
        return $cart;
    }
    private function updateQuantity($id, $quantity){
        $cart = Shopping_cart::with('product:id,name,stock,store_id')->select('id','product_id','quantity')->where('id', $id)->get()->first();
        if($cart){
            if($cart->product->stock >= $quantity){
                $cart->quantity = $quantity;
                $cart->save();
                return ok($cart);
            }elseif($cart->product->stock < 1){
                return not_acceptable('Error cart quantity !', $errors = ["quantity" => "the quantity of products must be more than or equal to 1"], $status = 406);
            }
            else{
                return not_acceptable('Insufficient product stock !', $errors = ["quantity" => "cart quantity can't more than product stock"], $status = 406);
            }
        }else{
 return error($message = 'not found !', $errors = ['id' => 'cart id not found !'], $status = 404);           
        }
    }
    private function checkoutCart($carts_id){
        $user_id = auth()->user()->id;
        if($carts_id){
            $cart = Shopping_cart::where('user_id', $user_id)->whereNotIn('id', $carts_id)->update(['is_checkout' => 0]);
            $cart = Shopping_cart::where('user_id', $user_id)->where('is_checkout', 0)->whereIn('id',  $carts_id)->update(['is_checkout' => 1]);
            return $cart;
        }
    }
    private function setLogistic(){
        // inisiasi dengan config array
        $config['api_key'] = '112fe48803760d5a4babb16bf9f7ac84';
        $config['account_type'] = 'starter';
        
        $rajaongkir = new Rajaongkir($config);

        $store_id = request('store_id');
        $courier = request('logistic');
        $user_id = auth()->user()->id;
        $weight = 0;
        $sub_weight = 0;
        $origin = null;
        $destination = null;

        $logistic_id = Logistic::select('id')->where('code', 'like', '%' . $courier . '%')->get()->first();
        if(request('OneAddress') == true){
            $logistic = Shopping_cart::with('product')
                            ->where('user_id', $user_id)
                            ->where('is_checkout', 1)
                            ->whereHas('product',function($q) use($store_id){
                                $q->where('store_id', $store_id);
                                })->update(['logistic_id' => $logistic_id->id]);
            $cart = Shopping_cart::with('product.store.store_address')
                    ->where('user_id', $user_id)
                    ->where('is_checkout', 1)
                    ->whereHas('product',function($q) use($store_id){
                        $q->where('store_id', $store_id);
                        })->get();
            foreach($cart as $prod){
                $sub_weight = $prod->product->weight * $prod->quantity;
                $weight = $sub_weight + $weight;
                $origin = $prod->product->store->store_address[0]->address_village->address_district->city_id;
            }
            $buyer_address = Address::with('address_village.address_district')
                            ->where('id', $cart[0]->address_id)
                            ->where('user_id', $user_id)->get()->first();
            $destination = $buyer_address->address_village->address_district->city_id;

        }elseif(request('MultipleAddress') == true){
            $setlogistic = Shopping_cart::find(request('cart_id'))
                            ->update([
                                    'logistic_id' => $logistic_id->id
                                    ]);
        }

        $data = $rajaongkir->getCost(['city' => $origin], ['city' => $destination], $weight*1000, $courier);
        return  $data;
    }
    private function setLogisticService(){
        $store_id = request('store_id');
        $user_id = auth()->user()->id;
        $shipping_cost = request('shipping_cost');
        $logistic_service = request('logistic_service');
        
        if(request('OneAddress') == true){
            $setlogistic = Shopping_cart::where('user_id', $user_id)
                            ->where('is_checkout', 1)
                            ->whereHas('product',function($q) use($store_id){
                                $q->where('store_id', $store_id);
                                })
                            ->update([
                                    'shipment_cost' => $shipping_cost, 
                                    'logistic_service' => $logistic_service]);
            return $setlogistic;
        }
        elseif(request('OneAddress') == false){
        }
        
    }
    private function setCartAddress(){
        if(request('oneAddress') == true){
            $data = Shopping_cart::where('user_id',  auth()->user()->id)->where('is_checkout', 1)->update(['address_id' => request('address_id')]);
        return $data;
        }else{
            $data = Shopping_cart::find(request('cart_id'));
            $data->address_id = request('address_id');
            $data->save();
            return $data;
        }
    }
}