<?php
namespace App\Http\Traits\user;

use Illuminate\Http\Request;
use App\Order_detail;
use App\Order_item;
use App\Order_shipment;
trait SaleTrait {
  public function getFilterSaleTrait($store_id){
    $orders = Order_detail::with(
              'order_item.product.photo', 
              'order.user:user_id,first_name,last_name', 
              'store:id,store_url,name', 
              'order_shipment_detail.logistic', 
              'order.order_payment.payment_status:id,name', 
              'order.order_payment.payment_type:id,name',
              'order.order_shipment.address_village.address_district.address_city.address_province',
              'order_status')
              ->where('store_id', $store_id)->whereNotIn('order_status_id', [1,11,12]);
    if(request()->has('order_id'))
      $orders->where('id','like','%'.request('order_id').'%');
    if(request()->has('buyer'))
      $orders->whereHas('order.user',function($q){
      $q->where('first_name','like','%'.request('buyer').'%');
      });
    if(request()->has('product'))
      $orders->whereHas('order_item.product',function($q){
      $q->where('name','like','%'.request('product').'%');
      });
    if(request()->has('status')){
      if(request('status') == "all"){
        //all
      }elseif(request('status') == "new"){
        $orders->where('order_status_id', 2);
      }elseif(request('status') == "process"){
        $orders->where('order_status_id', 3);
      }elseif(request('status') == "shipping"){
        $orders->where('order_status_id', 4);
      }elseif(request('status') == "delivered"){
        $orders->where('order_status_id', 5);
      }elseif(request('status') == "shipment"){
        $orders->whereIn('order_status_id', [4, 5]);
      }elseif(request('status') == "success"){
        $orders->where('order_status_id', 6);
      }elseif(request('status') == "complaint"){
        $orders->where('order_status_id', 13);
      }elseif(request('status') == "return"){
        $orders->where('order_status_id', 14);
      }elseif(request('status') == "reject"){
        $orders->where('order_status_id', 10);
      }
    }
    if(request('sortBy') == 'store')
      $orders->with(['order_item.product.store' => function ($q) {
      $q->orderBy('id', request('order'));
      }]);
      elseif(request('sortBy') == 'id')
      $orders->orderBy('id', request('order'));
    elseif(request('sortBy') == 'product')
      $orders->with(['order_item.product' => function ($q) {
      $q->orderBy('id', request('order'));
      }]);
    elseif(request('sortBy') == 'date')
      $orders->with(['order' => function ($q) {
      $q->orderBy('created_at', request('order'));
      }]);
    elseif(request('sortBy') == 'buyer')
      $orders->with(['order' => function ($q) {
      $q->orderBy('user_id', request('order'));
      }]);
    elseif(request('sortBy') == 'status')
      $orders->orderBy('order_status_id',request('order'));
    return $orders->paginate(request('pageLength'));
  }
  public function getSaleDetailTrait($id){
    $orderDetail = Order_detail::with(
                                      'order_item.product.photo', 
                                      'order.user:user_id,first_name,last_name', 
                                      'store:id,store_url,name', 
                                      'order_shipment_detail.logistic', 
                                      'order.order_payment:id,order_id,total_payment', 
                                      'order_status'
                                      )->where('id', $id)->first();
    if(!$orderDetail){
      return;
    }
    $address = \App\Address_village::join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
              ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
              ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
              ->select(
                        'address_villages.name as village',
                        'address_villages.zip_code as zip_code',
                        'address_districts.name as district',
                        'address_cities.name as city',
                        'address_provinces.name as province'
              )->where('address_villages.id', $orderDetail->order->order_shipment->village_id)->first();
    $orderDetail->order->order_shipment->address = $address;
    return $orderDetail;
  }
}