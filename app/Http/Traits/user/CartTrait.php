<?php
namespace App\Http\Traits\user;
use App\Cart;
use App\Shopping_cart;
trait CartTrait {

  private $store_field = 'product.store:id,name';
  private $store_logistic_field = 'product.store.store_logistic';
  private $product_field = 'product:id,store_id,name,price,weight,discount';

  public function getSelectedCartTrait(){
    $cart = Shopping_cart::with($this->store_field,$this->store_logistic_field, $this->product_field)->where('user_id', auth()->user()->id)->where('is_checkout', 1)->get()->groupBy('product.store_id')->toArray();
    if($cart){
      return $cart;
    }
  }
  public function deleteSelectedCartTrait(){
    $deleted = Shopping_cart::with($this->store_field,$this->store_logistic_field, $this->product_field)->where('user_id', auth()->user()->id)->where('is_checkout', 1)->delete();
    return $deleted ? 'deleted' : 'error';
  }
  public function getTotalPriceTrait($data){
    $totalPrice = 0;
    if($data){
      foreach ($data as $key => $value) {
        foreach($value as $item){
          $totalPrice = $totalPrice + ($item['product']['price'] * $item['quantity']);
        }
      }
    }
    return $totalPrice;
  }
  public function getTotalWeightTrait($data){
    $totalWeight = 0;
    if($data){
      foreach ($data as $key => $value) {
          $totalWeight = $totalWeight + ($value['product']['weight'] * $value['quantity']);
      }
    }
    return $totalWeight;
  }
  public function getSubTotalTrait($data){
    $subTotal = 0;
    if($data){
      foreach ($data as $key => $value) {
        $subTotal = $subTotal + ($value['product']['price'] * $value['quantity']);
      }
    }
    return $subTotal;
  }
  public function getTotalShipmentCostTrait($data){
    $subTotal = 0;
    if($data){
      foreach ($data as $key => $value) {
        $totalCost = $totalCost +  $value[0]['shipment_cost'];
      }
    }
    return $totalCost;
  }
  public function destroyBySelectedIdTrait($id){
      $cart = Shopping_cart::whereIn('id',  $id)->delete();
      if($cart){
          return ok(['message' => 'shopping cart successfully deleted !']);
      }else{
          return error($message = 'something errors !', $errors = [], $status = 500);
      }
  }
}