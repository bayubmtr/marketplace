<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Store;
use App\Logistic;
use App\Product;
use App\Shopping_cart;
use App\Address;
use App\Order;
use App\Order_promo;
use App\Order_detail;
use App\Order_item;
use App\Order_shipment;
use App\Order_shipment_detail;
use App\Order_payment;
use App\Http\Traits\user\InvoiceTrait;
use App\Http\Traits\user\OrderTrait;
use App\Http\Traits\user\AddressTrait;
use App\Http\Traits\user\CartTrait;
use Carbon\Carbon;

class PurchaseController extends UserController
{
    use InvoiceTrait;
    use OrderTrait;
    use AddressTrait;
    use CartTrait;
    protected $time;

    public function __construct(){
        $this->time = Carbon::now();
    }
    public function index(){
        $orders = null;
        if(request('type') == 'invoice'){
            $orders = $this->getFilterInvoiceTrait(auth()->user()->id,request()->all());
        }else if(request('type') == 'order')
            $orders = $this->getFilterOrderTrait(auth()->user()->id,request()->all());
        if($orders){
           return ok($orders);
        }
        return response()->json(['status' => 404, 'msg' => "something error"], 404);
    }
    public function store(Request $request){
        $data = $this->makeOrder(request()->all());
        if($data){
            return ok($data);
        }
    }
    public function show($id){
        $invoice_id = substr($id, 4);
        $order = $this->getInvoiceDetailTrait($invoice_id);
        if($order){
            return ok($order);
        }
        return error($message = 'not found !', $errors = ['id' => 'cart id not found !'], $status = 404);
    }
    public function update(Request $request, $id){
        if(request('cancelInvoice') == true){
            $invoice_id = substr($id, 4);
            $payment = Order_payment::where('id', $invoice_id)->where('payment_type_id', 1)->first();
            if(!$payment){
                return not_acceptable('something error !', $errors = ["payment" => "transactions that have been paid cannot be canceled"], $status = 406);
            }else{
                $order = Order_detail::where('order_id', $payment->order_id)->update([
                    'order_status_id'=> 11
                    ]);
                $payment->payment_status_id = 6;
                $payment->save();
                return ok(['message' => 'Order has been canceled !']);
            }
        }
        if(request('req_done') == true){
            $order = Order_detail::with('order', 'order_shipment_detail')->where('order_status_id','!=', 6)->whereHas('order',function($q){
                $q->where('user_id', auth()->user()->id);
            })->get()->first();
            if($order){
                $order->order_status_id = 6;
                $order->save();
                $updateOrder = \App\Store_statistic::where('store_id', $order->store_id)->first();
                $updateOrder->order = $updateOrder->order+1;
                $updateOrder->save();
                $receiver_id = \App\Store::find($order->store_id);
                $sendMoney = new \App\User_income;
                $sendMoney->user_id = $receiver_id->user_id;
                $sendMoney->amount = $order->sub_total_price+$order->order_shipment_detail->shipment_cost;
                $sendMoney->description = 'penjualan : '.$order->id;
                $sendMoney->save();
                return ok(['message' => 'Order has been done !']);
            }
            return ok(['message' => 'Ditolak !']);
        }
    }
    private function makeOrder(){
        $user_id = auth()->user()->id;
        $invoiceNumber = $this->makeInvoiceNumberTrait($user_id);
        $cart = $this->getSelectedCartTrait($user_id);
        $isOneAddress = 1;
        $totalPurchase = 0;
        $totalShipping = 0;
        $totalCoupon = 0;
        $use_promo = false;
        $promo_code = null;
        $obtain = null;
        $subTotal = 0;
        $cartItems = [];
        $items = array();
        $order = new Order();
        $i = 0;
        $order->user_id = $user_id;
        $order->expired_at = Carbon::now()->add(1, 'day');
        $order->save();
        foreach ($cart as $value) {
            $subTotal = $this->getSubTotalTrait($value);
            if($value[0]['promo_code']){
                $use_promo = true;
                $promo_code = $value[0]['promo_code'];
            }
        }
        if($use_promo){
            $data = \App\Promo::with('promo_term.promo_variable')->where('code', $promo_code)->where('is_active', 1)->first();
            if($data){
                if($data->promo_type == 'discount'){
                    if($subTotal >= $data->promo_term[0]->value){
                        $obtain = $subTotal*$data->obtain/100;
                        if($obtain > $data->max_obtain){
                            $obtain = $data->max_obtain;
                        }
                        $order_coupon = new Order_promo;
                        $order_coupon->order_id = $order->id;
                        $order_coupon->promo_code = $promo_code;
                        $order_coupon->obtain = $obtain;
                        $order_coupon->save();
                    }
                }
            }
        }
        if(!$cart){
            return (["status" => 404, "msg" => "no selected cart"]);
        }
        foreach ($cart as $key => $value) {
            $totalWeight = $this->getTotalWeightTrait($value);
            $subTotal = $this->getSubTotalTrait($value);
            $totalPurchase = $subTotal +  $totalPurchase;
            
            $order_detail = new Order_detail;
            $order_detail->order_id = $order->id;
            $order_detail->store_id = $key;
            $order_detail->order_status_id = 1;
            $order_detail->sub_total_price = $subTotal;
            $order_detail->sub_total_weight = $totalWeight;
            $order_detail->save();
            $order_detail_id = $order_detail->id;
            if($i == 0){
                $shipmentDetail = $this->getAddressTrait($value[0]['address_id']);
                $order_shipment = new Order_shipment;
                $order_shipment->order_id = $order->id;
                $order_shipment->village_id = $shipmentDetail->village_id;
                $order_shipment->recipient = $shipmentDetail->recipient;
                $order_shipment->address_detail = $shipmentDetail->address_detail;
                $order_shipment->phone_number = $shipmentDetail->phone_number;
                $order_shipment->save();
            }
            $i++;
            foreach($value as $item){
                $order_item = new Order_item;
                $order_item->order_detail_id = $order_detail_id;
                $order_item->product_id = $item['product_id'];
                $order_item->price = $item['product']['price'];
                $order_item->weight = $item['product']['weight'];
                $order_item->discount = $item['product']['discount'];
                $order_item->quantity = $item['quantity'];
                $order_item->save();
                $items[] = $item;
                array_push($cartItems, $item['id']);
            }
            $order_shipment = new Order_shipment_detail;
            $order_shipment->order_detail_id = $order_detail->id;
            $order_shipment->logistic_id = $value[0]['logistic_id'];
            $order_shipment->logistic_service = $value[0]['logistic_service'];
            $order_shipment->shipment_cost = $value[0]['shipment_cost'];
            $order_shipment->save();

            $totalShipping = $value[0]['shipment_cost'] + $totalShipping; 
            }
            $order_payment = new Order_payment();
            $order_payment->id = substr($invoiceNumber, 4);
            $order_payment->payment_type_id = 1;
            $order_payment->payment_status_id = 1;
            $order_payment->order_id = $order->id;
            $order_payment->total_payment = $totalPurchase+$totalShipping-$obtain;
            $order_payment->save();
            $deleted = $this->destroyBySelectedIdTrait($cartItems);
            return (["invoice_id" => $invoiceNumber, 'order_id' => $order->id, 'items' => $items]);
        }

}