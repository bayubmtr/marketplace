<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Order_detail;
use App\Order_payment;
use App\Order_shipment;
use App\Ref_payment_type;
use App\Product_statistic;
use App\store_statistic;
use App\Product;
use Midtrans;
use App\Http\Traits\user\InvoiceTrait;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class PaymentNotificationController extends UserController{
    use InvoiceTrait;
    public function notification(Request $request){
        if(!$request){
            return not_acceptable($message='Something error with your request !', $errors=['request' => 'request body needed !']);
        }
        $invoice_id = substr($request->order_id, 4);
        $invoice = $this->getInvoiceTrait($invoice_id);
        if(!$invoice){
            return error($message = 'not found !', $errors = ['id' => 'invoice not found !'], $status = 404);
        }
        $payment_id = Uuid::uuid1();
        $payment_type = $request->payment_type;
        $payment_type_id = null;
        $expired_at = null;
        $user_id = $invoice->order->user_id;
        
        $ref_payment_type = Ref_payment_type::where('name', $payment_type)->first();
        if(!$ref_payment_type){
            $new_payment_type = new Ref_payment_type;
            $new_payment_type->name = $payment_type;
            $new_payment_type->save();
            $payment_type_id = $new_payment_type->id;
        }else{
            $payment_type_id = $ref_payment_type->id;
        }
        $payment = Order_payment::find($invoice_id);
        $order = Order_detail::where('order_id', $payment->order_id);
        if($request->transaction_status == "pending"){
            if($payment->payment_status_id == 1){
                $expired_at = date('Y-m-d H:i:s',strtotime('+3 hour',strtotime($request->transaction_time)));
                $payment->payment_status_id = 2;
                $payment->payment_type_id = $payment_type_id;
                $payment->expired_at = $expired_at;
            }
            if($payment_type_id == 2){
                $payment->meta = json_encode($request->va_numbers);
            }
        }elseif($request->transaction_status == "settlement"){
            if($payment->payment_status_id == 1 || $payment->payment_status_id == 2){
                $payment->payment_status_id = 3;
                $payment->expired_at = $expired_at;
                $order->where('order_status_id', 1)
                            ->update(['order_status_id' => 2]);
                $shipment = Order_shipment::where('order_id', $payment->order_id)
                                            ->update(['expired_at' => date('Y-m-d',strtotime('+2 day',strtotime($request->transaction_time)))]);
                $order_detail = Order_detail::where('order_id', $payment->order_id)->get();
                foreach ($order_detail as $key => $value) {
                    foreach($value->order_item as $itm){
                        $stock = Product::find($itm->product_id);
                        $stock->stock = $stock->stock-$itm->quantity;
                        $stock->save();
                        $store_statistic = Store_statistic::where('store_id', $value->store_id)->first();
                        $store_statistic->sold_count = $store_statistic->sold_count+1;
                        $store_statistic->save();
                        $product_statistic = Product_statistic::where('product_id', $itm->product_id)->first();
                        $product_statistic->sold_count = $product_statistic->sold_count+1;
                        $product_statistic->save();
                    }
                }                      
            }
        }elseif($request->transaction_status == "expire"){
            if($payment->payment_status_id == 1){
                $payment->payment_status_id = 4;
                $payment->expired_at = $expired_at;
                $order->where('order_status_id', 1)
                            ->update(['order_status_id' => 12]);
            }
        }
        $payment->save();
        return response()->json(['success' => $payment],200);
    }
    public function paymentSuccess(Request $request){
        if($request->order_id){
            return redirect()->away('https://byxmart.store/payment/status?order_id='.$request->order_id);
        }
        $response = json_decode($request->get("response"), true);
        if($response){
            return redirect()->away('https://byxmart.store/payment/status?order_id='.$response["order_id"]);
        }
        if($request->id){
            return redirect()->away('https://byxmart.store/payment/status?order_id='.$request->id);
        }
    }
}