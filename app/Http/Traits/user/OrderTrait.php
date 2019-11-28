<?php
namespace App\Http\Traits\user;
use App\Invoice;
use App\Address_village;
use App\Order;
use App\Order_detail;
use App\Order_item;
use App\Order_shipment;
use App\Product;
use Carbon;
trait OrderTrait {
  public function getOrderTrait($order_id){
    $order = Order::with(
                        'order_payment',
                        'store:id,name',
                        'order_item.product:id,name', 
                        'order_shipment:id,order_detail_id,logistic_service,shipment_cost,logistic_id', 
                        'order_shipment.logistic'
                        )->where('id', $order_id)->get()->first();
    return $order;
  }
  public function getOrderDetailTrait($order_id){
    $invoice = Order_detail::with(
                                  'order.order_payment.payment_type',
                                  'order.order_payment.payment_status',
                                  'order_status',
                                  'store:id,name',
                                  'order_item.product:id,name', 
                                  'order_item.product.photo',
                                  'order.order_shipment', 
                                  'order_shipment_detail.logistic'
                                  )->where('id', $order_id)->get()->first();
    $address = \App\Address_village::join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
    ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
    ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
    ->select(
              'address_villages.name as village',
              'address_villages.zip_code as zip_code',
              'address_districts.name as district',
              'address_cities.name as city',
              'address_cities.type as city_type',
              'address_provinces.name as province'
    )->where('address_villages.id', $invoice->order->order_shipment->village_id)->first();
    $invoice->order->order_shipment->address = $address;
    return $invoice;
  }
  public function getFilterOrderTrait($user_id){
    $orders = Order_detail::with(
                          'order.order_payment.payment_type',
                          'order.order_payment.payment_status',
                          'order_status',
                          'store:id,name',
                          'order_item.product:id,name', 
                          'order_item.product.photo',
                          'order_shipment_detail', 
                          'order_shipment_detail.logistic'
                          )->whereHas('order',function($q) use($user_id){
                              $q->where('user_id', $user_id);
                            });
    if(request()->has('invoice_id'))
    $orders->whereHas('order.order_payment',function($q){
      $q->where('id','like','%'.request('invoice_id').'%');
      });
    if(request()->has('store'))
      $orders->whereHas('store',function($q){
      $q->where('name','like','%'.request('store').'%');
      });
    if(request()->has('product'))
      $orders->whereHas('order_item.product',function($q){
      $q->where('name','like','%'.request('product').'%');
      });
    if(request()->has('order_status')){
      if(request('order_status') == 'seller-processed'){
        $orders->whereIn('order_status_id', [2, 3]);
      }else if(request('order_status') == 'payment'){
        $orders->where('order_status_id', 1);
      }else if(request('order_status') == 'request'){
        $orders->where('order_status_id', 2);
      }else if(request('order_status') == 'process'){
        $orders->where('order_status_id', 3);
      }else if(request('order_status') == 'shipping'){
        $orders->where('order_status_id', 4);
      }else if(request('order_status') == 'delivered'){
        $orders->where('order_status_id', 5);
      }else if(request('order_status') == 'shipment'){
        $orders->whereIn('order_status_id', [4, 5]);
      }else if(request('order_status') == 'success'){
        $orders->where('order_status_id', 6);
      }else if(request('order_status') == 'failed'){
        $orders->whereIn('order_status_id', [10, 11, 12]);
      }else if(request('order_status') == 'reject'){
        $orders->where('order_status_id', 10);
      }else if(request('order_status') == 'cancel'){
        $orders->where('order_status_id', 11);
      }else if(request('order_status') == 'expired'){
        $orders->where('order_status_id', 12);
      }else if(request('order_status') == 'complaint'){
        $orders->where('order_status_id', 13);
      }else if(request('order_status') == 'return'){
        $orders->where('order_status_id', 14);
      }
    }
    if(request()->has('payment_status'))
      $orders->whereHas('order.order_payment.payment_status',function($q){
      $q->where('name','like','%'.request('payment_status').'%');
      });
    if(request('sortBy') == 'store')
      $orders->with(['order_item.product.store' => function ($q) {
      $q->orderBy('id', request('order'));
      }]);
    elseif(request('sortBy') == 'product')
      $orders->with(['order_item.product' => function ($q) {
      $q->orderBy('id', request('order'));
      }]);
    elseif(request('sortBy') == 'price')
      $orders->with(['order.order_payment' => function ($q) {
      $q->orderBy('total_payment', request('order'));
      }]);
    elseif(request('sortBy') == 'date')
      $orders->orderBy('created_at', request('order'));
    elseif(request('sortBy') == 'status')
      $orders->orderBy('order_status_id',request('order'));
		return $orders->paginate(request('pageLength'));
  }
  public function cancelOrderTrait($orderId){
    $order = Order::find($orderId);
    $order->order_status_id = 8;
    $order->payment->payment_status_id = 5;
    $order->payment->save();
    $order->save();
    return $order;
  }
}