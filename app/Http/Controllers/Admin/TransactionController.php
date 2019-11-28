<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Order_detail;

class TransactionController extends AdminController
{


	public function index(){
		$orders = Order_detail::with(
                                    'order.order_payment.payment_type',
                                    'order.order_payment.payment_status',
                                    'order.user:user_id,first_name,last_name',
                                    'order_status',
                                    'store:id,name',
                                    'order_item.product:id,name', 
                                    'order_item.product.photo',
                                    'order.order_shipment', 
                                    'order_shipment_detail.logistic'
        );
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
        if(request()->has('startDate') ){
            $orders->whereHas('order',function($q){
                $q->whereDate('created_at', '>=', request('startDate'));
            });
        }
        if(request()->has('endDate') ){
            $orders->whereHas('order',function($q){
                $q->whereDate('created_at', '<=', request('endDate'));
            });
        }
        if(request()->has('payment_status'))
          $orders->whereHas('order.order_payment.payment_status',function($q){
          $q->where('status','like','%'.request('payment_status').'%');
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
  public function update(Request $request, $id){
    $data = Order_detail::find($id);
    $data->order_status_id = request('status');
    $data->save();
    return ok(['message' => 'Transaksi '.$data->id.' Berhasil Diubah Status']);
  }
}