<?php

namespace App\Http\Controllers\User\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\user\SaleTrait;
use App\Order_detail;
use Carbon\Carbon;

class SaleController extends Controller
{
    use SaleTrait;
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        if ($store) {
            $order = $this->getFilterSaleTrait($store->id,request()->all());
            return ok($order);
        }else{
            return response()->json(['status' => 204, 'msg' => 'No store found !']);
        }
    }
    public function create()
    {
        return 'create';
    }
    public function store(Request $request)
    {
        return 'store';
    }
    public function show($id)
    {
        $sale = $this->getSaleDetailTrait($id);
        return $sale;
    }
    public function edit($id)
    {
        return 'edit';
    }
    public function update(Request $request, $id)
    {
        if(request('type') == 'accept'){
            $accept = $this->acceptOrder($id);
            if(!$accept){
                return not_acceptable('Error', ['msg' => 'error when trying to process request !']);
            }
            return ok(['message' => 'Success Accept Order !']);
        }
        if(request('type') == 'reject'){
            if(!request('reason')){
                return not_acceptable('Error', ['reason' => 'Reason needed for cancel order !']);
            }
            $reject = $this->rejectOrder($id,request('reason'));
            if(!$reject){
                return not_acceptable('Error', ['msg' => 'error when trying to process request !']);
            }
            return ok(['message' => 'Success Reject Order !']);
        }
        if(request('type') == 'send'){
            if(!request('tracking_number')){
                return not_acceptable('Error', ['tracking_number' => 'Tracking Number Needed For cancel Send Order !']);
            }
            $send = $this->sendOrder($id, request('tracking_number'));
            if(!$send){
                return not_acceptable('Error', ['msg' => 'error when trying to process request !']);
            }
            return ok(['message' => 'Success Send Order !']);
        }
    }
    public function destroy($id)
    { 
    }
    private function acceptOrder($id){
        $accept = Order_detail::where('id', $id)->first();
        if($accept){
            $store_statistic = \App\Store_statistic::where('store_id', $accept->store_id)->first();
            $store_statistic->order_accept = $store_statistic->order_accept+1;
            $store_statistic->save();
            $accept->order_status_id = 3;
            $accept->save();
            return 'ok';
        }
        return;
    }
    private function rejectOrder($id, $reason){
        $accept = Order_detail::with('order_item')->where('id', $id)->update(['order_status_id' => 10, 'meta' => $reason]);
        $data = Order_detail::with('order_item')->where('id', $id)->get();
        if($accept){
            foreach ($data as $key => $xxx) {
                foreach ($xxx->order_item as $value) {
                    $stock = \App\Product::find($value->product_id);
                    $stock->stock = $stock->stock+$value->quantity;
                    $stock->save();
                    $store_statistic = \App\Store_statistic::where('store_id', $xxx->store_id)->first();
                    $store_statistic->sold_count = $store_statistic->sold_count-1;
                    $store_statistic->save();
                    $product_statistic = \App\Product_statistic::where('product_id', $value->product_id)->first();
                    $product_statistic->sold_count = $product_statistic->sold_count-1;
                    $product_statistic->save();
                }
            }
            return 'ok';
        }
        return;
    }
    private function sendOrder($id, $tracking_number){
        $accept = Order_detail::find($id);
        $accept->order_status_id = 4;
        $accept->order_shipment_detail->tracking_number = $tracking_number;
        $accept->order_shipment_detail->sent_at = Carbon::now();
        $accept->save();
        $accept->order_shipment_detail->save();
        if($accept){
            return 'ok';
        }
        return;
    }
}
