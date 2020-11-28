<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="{{ asset('web/js/app.js') }}" defer></script>
        <link href="{{ asset('web/css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
   </head>
    <body>
    <div id="app" class="row justify-content-center">
        <div id="invoice-template" class="card-body col-md-10 col-xs-10 pt-5">
                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-5 text-center text-md-left">
                    <h1>BYXmart</h1>
                    </div>
                    <div class="col-2 text-center">
                    <a href="" @click.prevent="printme" target="_blank" class="btn btn-default no-print"><i class="fa fa-print"></i> Print</a>
                    </div>
                    <div class="col-5 text-center text-md-right">
                        <h2>Bukti Pembayaran</h2>
                        <div>{!! QrCode::size(100)->generate($invoice->id); !!}</div>
                        <h2>{{$invoice->id}}</h2>
                    </div>
                </div>
                <!-- Invoice Items Details -->
                <div id="invoice-items-details" class="pt-2">
                <div class="row">
                        <div class="table-responsive col-sm-12">
                            <table class="table table-bordered">
                            <thead>
                                <tr class="table-info">
                                    <th colspan="2" style="width: 50%" class="text-center">Pembayaran</th>
                                    <th colspan="2" style="width: 50%" class="text-center">Pembeli</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 15%">No Tagihan</td>
                                    <td style="width: 35%">{{$invoice->id}}</td>
                                    <td>Nama Pembeli</td>
                                    <td>{{$invoice->order->user->first_name}} {{$invoice->order->user->last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Tipe Pembayaran</td>
                                    <td>{{$invoice->payment_type->name}}</td>
                                    <td rowspan="2">Alamat Pengiriman</td>
                                    <td rowspan="2">{{$invoice->order->order_shipment->address_detail}} 
                                       <div>
                                            Kel. {{$invoice->order->order_shipment->address->village}},
                                            Kec. {{$invoice->order->order_shipment->address->district}},
                                            @if ($invoice->order->order_shipment->address->city_type === 'Kabupaten')
                                            <span>
                                                Kab. {{$invoice->order->order_shipment->address->city}},
                                            </span>
                                            @elseif ($invoice->order->order_shipment->address->city_type === 'Kota')
                                            <span>
                                                Kota {{$invoice->order->order_shipment->address->city}},
                                            </span>
                                            @endif
                                            Provinsi {{$invoice->order->order_shipment->address->province}} - {{$invoice->order->order_shipment->address->zip_code}}
                                       </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Transaksi</td>
                                    <td>{{$invoice->updated_at}}</td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                    <?php $sum_tot_Price = 0 ?>
                    <?php $sum_tot_ship_cost = 0 ?>
                        @foreach($invoice->order->order_detail as $order)
                        <?php $sum_tot_Price += $order->sub_total_price ?>
                        <?php $sum_tot_ship_cost += $order->order_shipment_detail->shipment_cost ?>
                        <div class="col-sm-12">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr class="table-dark">
                                    <th colspan="5" class="border border-secondary text-center" class="text-center" style="width: 15%"><span class="text-dark">No Pesanan : {{$order->id}}</span></th>
                                </tr>
                                    <td class="text-right" style="width: 50%">Penjual</td>
                                    <td style="width: 50%">{{$order->store->name}}</td>
                                </thead>
                            </table>
                            <table class="table table-bordered">
                            @if ($loop->first)
                            <thead> 
                                <tr class="table-info">
                                    <th style="width:5%">NO</th>
                                    <th style="width: 45%">Nama Barang</th>
                                    <th style="width: 15%"class="text-right">Harga</th>
                                    <th style="width: 10%" class="text-right">Diskon</th>
                                    <th style="width: 25%" class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            @endif
                            <tbody>
                                @foreach ($order->order_item as $item)
                                <tr>
                                    <td scope="row" style="width: 5%">{{$loop->iteration}}</td>
                                    <td style="width: 45%" class="text-truncate">{{$item->product->name}}</td>
                                    <td class="text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
                                    <td class="text-center">{{ $item->discount }}%</td>
                                    <td class="text-right">Rp {{ number_format($order->sub_total_price,0,',','.') }}</td>
                                </tr>
                                @endforeach
                                @if ($loop->last)
                                <tr>
                                    <td></td>
                                    <td colspan="3" scope="row">Biaya Pengiriman {{$order->order_shipment_detail->logistic->name}} - {{$order->order_shipment_detail->logistic_service}}</td>
                                    <td class="text-right">Rp {{ number_format($order->order_shipment_detail->shipment_cost,0,',','.') }}</td>
                                </tr>
                                @endif
                                <tr class="table-dark ">
                                <td colspan="5" style="width: 40%"><span class="text-dark">Catatan : {{$item->note}}</span></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-7 col-sm-12 text-center text-md-left">
                            <div>
                                Deklarasi :
                            </div>
                            <div>
                                <small>byxmart.store merupakan pasar online yang menjadi perantara antar penjual dan pembeli. </small>
                            </div>
                            <div>
                                <small>byxmart.store menyatakan bahwa semua keterangan adalah benar dan tepat. </small>
                            </div>
                            <div>
                                <small>Bukti pembayaran ini dihasilkan oleh sistem dan tidak memerlukan tanda tangan.</small>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <p class="lead">Rincian Pembayaran:</p>
                            <div class="table-responsive">
                                <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Total Harga Barang</td>
                                        <td class="text-right">Rp {{ number_format($sum_tot_Price,0,',','.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Pengiriman</td>
                                        <td class="text-right">Rp {{ number_format($sum_tot_ship_cost,0,',','.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-800">Total Pembayaran</td>
                                        <td class="text-bold-800 text-right">Rp {{ number_format($invoice->total_payment,0,',','.') }}</td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer -->
                <div id="invoice-footer">
                    <div class="row">
                    </div>
                </div>
                <!--/ Invoice Footer -->

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>