<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Midtrans;
use Response;

class PaymentController extends PurchaseController
{
    public function index(){
        if(request()->has('getToken')){
            $token = $this->getToken(substr(request('getToken'), 4));
            return ok($token);
        }
    }
    private function getToken($invoice_id){
        if(!$invoice_id){
            return response()->json(['error' => "something error"]);
        }
        $data = $this->getInvoiceTrait($invoice_id);
        $item_details = [];
        $shipmentCost = 0;
        $logistic = [];
        $promo = [];
        foreach ($data->order->order_promo as $key => $value) {
            $promo[] = array(
                'id' => "Promo",
                'quantity' => 1,
                'name' => $value->promo_code,
                'price' => '-'.$value->obtain,
            );
        }
        foreach ($data->order->order_detail as $key => $value) {
            $shipmentCost = $shipmentCost + $value->order_shipment_detail->shipment_cost;
            $logistic[] = array(
                'id' => "LGT-".$value->order_shipment_detail->logistic_id,
                'quantity' => 1,
                'name' => strtoupper($value->order_shipment_detail->logistic->code." - ".$value->order_shipment_detail->logistic_service),
                'price' => $value->order_shipment_detail->shipment_cost,
            );
            
            foreach ($value->order_item as $item) {
                $product_name = strlen($item->product->name) > 30 ? substr($item->product->name,0,30) : $item->product->name;
                $item_details[] = array(
                    'id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'name' => $product_name,
                    'price' => $item->price,
                    "merchant_name" => $value->store->name
                    );
            }
        }
        $splitName = explode(' ', auth()->user()->profile->full_name, 2);
        $first_name = $splitName[0];
        $last_name = !empty($splitName[1]) ? $splitName[1] : '';
        
        $customer_details = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->profile->phone_number
        ];
        $custom_expiry = [
            'start_time' => date("Y-m-d H:i:s O", time()),
            'unit' => 'hour',
            'duration' => 3
        ];
        $transaction_details = [
            'order_id' => 'INV-'.$invoice_id,
            'gross_amount' => $data->total_payment
        ];
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => array_merge($item_details,$promo,$logistic),
            'customer_details' => $customer_details,
            'expiry' => $custom_expiry,
        ];
        $token = Midtrans::getSnapToken($transaction_data);
        return $token;
    }
    public function show($id){
        if(!$id){
            return response()->json(['error' => "something error"]);
        }
        $order_id = substr($id, 4);
        $status = Midtrans::status($order_id);
        if($status){
            return ok($status);
        }
        return error($message = 'not found !', $errors = ['id' => 'cart id not found !'], $status = 404);      
    }
    public function cancelPayment($order_id){
        if(!$order_id){
            return response()->json(['error' => "something error"]);
        }
        $cancel = Midtrans::cancel($order_id);
        return Response::json($cancel);
    }
    public function getExpirePayment($order_id){
        if(!$order_id){
            return response()->json(['error' => "something error"]);
        }
        $expire = Midtrans::expire($order_id);
        return Response::json($expire);
    }
    
}