<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Order_payment;
use App\Order_detail;
use App\Http\Traits\user\OrderTrait;
use App\Http\Traits\user\InvoiceTrait;
use peal\barcodegenerator\Server\BarCodeServer;

class InvoiceController extends UserController
{
    use OrderTrait;
    use InvoiceTrait;
    public function index($id){
        $type = substr($id, 0, 3);
        $invoice_id = substr($id, 4);
        if($type == 'INV'){
            $invoice = $this->getInvoiceByPayment($invoice_id);
            return view('invoice_payment', ['invoice' => $invoice]);
        }else{
            $invoice = $this->getInvoiceByOrder($id);
            return view('invoice_order', ['invoice' => $invoice]);
        }
    }
    private function getInvoiceByPayment($id){
        $invoice = $this->getInvoiceDetailTrait($id);
        return $invoice;
    }
    private function getInvoiceByOrder($id){
        $invoice = $this->getOrderDetailTrait($id);
        return $invoice;
    }
    private function getBarcode($id){
        \QrCode::size(500)
            ->format('png')
            ->generate('ItSolutionStuff.com', public_path('images/qrcode.png'));
    }
}