<template>
	<div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <b-card>
                    <div slot="header">
                        <strong>Daftar Transaksi</strong>
                    </div>

                        <div class="row m-t-40">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Id Pembelian</label>
                                    <input class="form-control" v-model="filterItemForm.id" @blur="getItems">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Id Tagihan</label>
                                    <input class="form-control" v-model="filterItemForm.invoice_id" @blur="getItems">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Pembeli</label>
                                    <input class="form-control" v-model="filterItemForm.buyer" @blur="getItems">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Penjual</label>
                                    <input class="form-control" v-model="filterItemForm.store" @blur="getItems">
                                </div>
                            </div>
                             <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Produk</label>
                                    <input class="form-control" v-model="filterItemForm.product" @blur="getItems">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" v-model="filterItemForm.order_status" @change="getItems">
                                        <option value="">All</option>
                                        <option value="payment">Belum Dibayar</option>
                                        <option value="request">Memesan Produk</option>
                                        <option value="process">Diproses Penjual</option>
                                        <option value="shipping">Sedang Dikirim</option>
                                        <option value="delivered">Sudah Sampai</option>
                                        <option value="success">Selesai</option>
                                        <option value="failed">gagal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterItemForm.sortBy" @change="getItems">
                                        <option value="id">Id Transaction</option>
                                        <option value="buyer">Buyer</option>
                                        <option value="store">Store</option>
                                        <option value="status">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Order</label>
                                    <select name="order" class="form-control" v-model="filterItemForm.order" @change="getItems">
                                        <option value="asc">Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Tanggal Awal</label>
                                        <datepicker :format="customFormatter" v-on:selected="setStartDate($event)" class="pb-2"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Tanggal Akhir</label>
                                        <datepicker :format="customFormatter" v-on:selected="setEndDate($event)" class="pb-2"></datepicker>
                                </div>
                            </div>
                        </div>
                        <h4 class="card-title">Daftar Transaksi</h4>
                        <h6 class="card-subtitle" v-if="items.data">Total {{items.total}} transaksi ditemukan !</h6>
                        <h6 class="card-subtitle" v-else>Tidak ada hasil !</h6>
                        <div class="float-right mb-1">
                            <download-excel
                                :data="items.data"
                                :fields = "json_fields"
                                :name="'transaksi-'+today+'.xls'">
                                <button>Export ke Excel</button>
                            </download-excel>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" v-if="items.total">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">Id</th>
										<th style="width:80px;">Pembeli</th>
                                        <th style="width:80px;">Penjual</th>
                                        <th style="width:200px;">Produk</th>
                                        <th style="width:100px;">Total Belanja</th>
                                        <th style="width:50px;">Status</th>
                                        <th style="width:50px;">Tanggal</th>
                                        <th style="width:5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in items.data" :key="item.id">
                                        <td v-text="item.id"></td>
										<td v-text="item.order.user.first_name"></td>
                                        <td v-text="item.store.name"></td>
                                        <td>
                                            <tr v-for="(item2, index) in item.order_item" :key="index">
                                                <div>
                                                    {{item2.quantity}} x {{item2.product.name}}
                                                </div>
                                            </tr>
                                        </td>
                                        <td class="text-right">{{item.sub_total_price+item.order_shipment_detail.shipment_cost | formatRupiah}}</td>
                                        <td v-html="getItemStatus(item.order_status_id)"></td>
                                        <td>{{item.order.created_at | moment("D-MMMM-YY") }}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm" @click.prevent="changeStatus(item.id)" data-toggle="tooltip" title="Ubah Status"><i class="fa fa-pencil"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-8">
                                    <pagination :data="items" :limit=3 v-on:pagination-change-page="getItems"></pagination>
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <select name="pageLength" class="form-control" v-model="filterItemForm.pageLength" @change="getItems" v-if="items.total">
                                            <option value="5">10 per page</option>
                                            <option value="10">20 per page</option>
                                            <option value="25">50 per page</option>
                                            <option value="100">100 per page</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                </b-card>
            </div>
        </div>
        <b-modal id="changeStatus" title="Ubah Status Transaksi" no-close-on-backdrop hide-footer centered>
            <b-row>
                <b-col cols="3">
                    Status
                </b-col>
                <b-col cols="9">
                    <select v-model="status" class="form-control">
                        <option :value="1">Belum Dibayar</option>
                        <option :value="2">Menunggu Respon Penjual</option>
                        <option :value="5">Terkirim</option>
                        <option :value="6">Selesai</option>
                        <option :value="12">Kadaluarsa</option>
                        <option :value="13">Dikomplain Pembeli</option>
                        <option :value="14">Dikembalikan</option>
                    </select>
                    <b-button v-on:click="updateStatus(transaction_id)" class="float-right mt-2" variant="outline-primary">Ubah Status</b-button>
                </b-col>
            </b-row>
        </b-modal>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import pagination from 'laravel-vue-pagination'
import helper from '../../services/helper'
import ClickConfirm from 'click-confirm'

    export default {
        components : { pagination, ClickConfirm, Datepicker},
        data() {
            return {
                items: {},
                status : '',
                transaction_id : '',
                filterItemForm: {
                    sortBy : 'id',
                    order: 'desc',
                    id : '',
                    invoice_id : '',
                    buyer : '',
                    store : '',
                    product : '',
                    startDate : '',
                    endDate : '',
                    pageLength: 10,
                    order_status : '',
                },
                excel : {},
                json_fields: {
                'ID Pesanan': 'id',
                'ID Tagihan': 'order.order_payment.id',
                'Pembeli': 'order.user.first_name',
                'Penjual': 'store.name',
                'Total Belanja': 'sub_total_price',
                'Biaya Kirim': 'order_shipment_detail.shipment_cost',
                'Penerima': 'order.order_shipment.recipient',
                'No HP Penerima': 'order.order_shipment.phone_number',
                'status': 'order_status.name',
                'tanggal': 'order.created_at'
            },
            }
        },
        mounted() {
            this.getItems();
        },
        computed: {
            today(){
                return Vue.moment().format("D-MMMM-YY.h-m");
            }
        },
        methods: {
            getItems(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterItemForm);
                axios.get('/api/admin/transactions?page=' + page + url)
                    .then(response => this.items = response.data );
            },
            exportTransaction(){
                axios.get('/api/admin/transactions')
                .then(response => {
                    this.excel = response.data.data
                })
                .catch(err => {
                    console.error(err); 
                })
            },
            changeStatus(id){
                this.transaction_id = id;
                this.$bvModal.show('changeStatus');
            },
            updateStatus(id){
                axios.patch('/api/admin/transactions/'+id, {status : this.status})
                .then(res => {
                    this.getItems();
                    toastr.success(res.data.message);
                    this.$bvModal.hide('changeStatus');
                })
                .catch(err => {
                    console.error(err); 
                })
            },
            customFormatter(date) {
                return Vue.moment(date).format('YYYY-MM-DD ');
            },
            setStartDate(data){
                this.filterItemForm.startDate = this.customFormatter(data)
            },
            setEndDate(data){
                this.filterItemForm.endDate = this.customFormatter(data)
            },
            getItemStatus(item){
                if(item == '1')
                    return '<span class="badge badge-warning">Pembayaran</span>';
                else if(item == '2')
                    return '<span class="badge badge-warning">Memesan</span>';
                else if(item == '3')
                    return '<span class="badge badge-primary">Diproses</span>';
                else if(item == '4')
                    return '<span class="badge badge-primary">Dikirim</span>';
                else if(item == '5')
                    return '<span class="badge badge-success">Terkirim</span>';
                else if(item == '6')
                    return '<span class="badge badge-success">Selesai</span>';
                else if(item == '10')
                    return '<span class="badge badge-danger">Ditolak</span>';
                else if(item == '11')
                    return '<span class="badge badge-danger">Dibatalkan</span>';
                else if(item == '12')
                    return '<span class="badge badge-danger">Kadaluarsa</span>';
                else if(item == '13')
                    return '<span class="badge badge-danger">Dikomplain</span>';
                else if(item == '14')
                    return '<span class="badge badge-danger">Dikembalikan</span>';
                else
                    return;
            }
        },
        filters: {
            moment(date) {
                return helper.formatDateTime(date);
            },
            ucword(value) {
                return helper.ucword(value);
            },
            formatRupiah(value){
                return helper.formatRupiah(value);
            },
            slug(value){
                return helper.toSlug(value);
            }
        },
        watch: {
            filterItemForm:{
                handler(val){
                    this.getItems()
                },
                deep: true
            }
        },
    }
</script>
