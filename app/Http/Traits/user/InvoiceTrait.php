<?php
namespace App\Http\Traits\user;
use App\Order_payment;
use Carbon;
trait InvoiceTrait {
  public function makeInvoiceNumberTrait($user_id){
    return 'INV-'.$this->time->format('ymd').rand(100,999).$this->time->format('His');
  }
  public function getInvoiceTrait($invoice_id){
    $invoice = Order_payment::find($invoice_id);
    return $invoice;
  }
  public function getInvoiceDetailTrait($order_id){
    $invoice = Order_payment::with(
                                  'payment_status:id,name',
                                  'payment_type:id,name',
                                  'order.order_detail.order_item.product.photo:id,product_id,small', 
                                  'order.order_detail.store:id,store_url,name,user_id', 
                                  'order.order_detail.order_shipment_detail.logistic', 
                                  'order.order_detail.order_status'
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
  public function getFilterInvoiceTrait($user_id){
    if(request('status') == 'process' || request('order_status') == 'success'){
      $order= $this->getAllOrderDetailTrait($user_id);
      return $order;
    }
    $orders = Order_payment::with(
                          'payment_status:id,name',
                          'payment_type:id,name',
                          'order.order_detail.order_item.product.photo:id,product_id,small', 
                          'order.order_detail.store:id,store_url,name,user_id', 
                          'order.order_detail.order_shipment_detail', 
                          'order.order_detail.order_status'
                          )->whereHas('order',function($q)use($user_id){
                            $q->where('user_id', $user_id);
                          });
    if(request()->has('invoice_id'))
      $orders->where('id','like','%'.request('invoice_id').'%');
    if(request()->has('store'))
      $orders->whereHas('order.order_detail.store',function($q){
      $q->where('name','like','%'.request('store').'%');
      });
    if(request()->has('product'))
      $orders->whereHas('order.order_detail.order_item.product',function($q){
      $q->where('name','like','%'.request('product').'%');
      });
    if(request()->has('order_status'))
      $orders->whereHas('order.order_detail.order_status',function($q){
      $q->where('name','like','%'.request('order_status').'%');
      });
    if(request()->has('payment_status'))
      $orders->whereHas('payment.payment_status',function($q){
      $q->where('name','like','%'.request('payment_status').'%');
      });
    if(request('sortBy') == 'store')
      $orders->with(['order.order_detail.order_item.product.store' => function ($q) {
      $q->orderBy('id', request('order'));
      }]);
    elseif(request('sortBy') == 'product')
      $orders->with(['order.order_detail.order_item' => function ($q) {
      $q->orderBy('product_id', request('order'));
      }]);
    elseif(request('sortBy') == 'price')
      $orders->orderBy('total_payment', request('order'));
    elseif(request('sortBy') == 'date'){
      $orders->orderBy('created_at', request('order'));
    }
    elseif(request('sortBy') == 'status')
      $orders->with(['order.order_detail' => function ($q) {
        $q->orderBy('order_status_id',request('order'));
      }]);
		return $orders->paginate(request('pageLength'));
  }
}