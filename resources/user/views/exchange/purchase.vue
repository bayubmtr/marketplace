<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Transaksi Pembelian</span>
        </div>
        <div class="row m-t-40">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Id Tagihan</label>
                    <input class="form-control" v-model="filterItemForm.invoice_id" @blur="getItems">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Toko</label>
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
                        <option value="all">Semuanya</option>
                        <option value="payment">Menunggu pembayaran</option>
                        <option value="request">Menunggu tanggapan penjual</option>
                        <option value="process">Sedang diproses penjual</option>
                        <option value="shipping">Sedang dikirim</option>
                        <option value="delivered">Sudah terkirim</option>
                        <option value="done">Selesai</option>
                        <option value="reject">Ditolak penjual</option>
                        <option value="cancel">Dibatalkan pembeli</option>
                        <option value="expired">Kadaluarsa</option>
                        <option value="compaint">Dikomplain</option>
                        <option value="returned">Dikembalikan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Sortir</label>
                    <select name="sortBy" class="form-control" v-model="filterItemForm.sortBy" @change="getItems">
                        <option value="date">Tanggal</option>
                        <option value="price">Total tagihan</option>
                        <option value="store">Penjual</option>
                        <option value="product">Produk</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Urutkan</label>
                    <select name="order" class="form-control" v-model="filterItemForm.order" @change="getItems">
                        <option value="asc">A - Z</option>
                        <option value="desc">Z - A</option>
                    </select>
                </div>
            </div>
        </div>
        <h6 class="card-subtitle" v-if="orders.total > 0">Total : {{orders.total}} transaksi</h6>
        <h6 class="card-subtitle" v-else>Tidak Ada Data ! </h6>
            <template v-if="orders.data">
                <template v-for="(item, index) in orders.data">
                    <b-card :key="index" class="mt-2">
                        <div slot="header">
                            <b-row>
                                <b-col cols="4" class="text-truncate">
                                    <div class="text-muted">No. Tagihan</div>
                                    <div><router-link :to="'/purchase/'+item.id">
                                        <strong class="mouse-pointer">{{item.id}}</strong>
                                        </router-link>
                                    </div>
                                    <div>
                                        <span class="text-muted">{{item.created_at | moment("dddd, Do MMMM YYYY, h:mm")}}</span>
                                    </div>
                                </b-col>
                                <b-col cols="2" class="text-truncate">
                                    <div>
                                        <span class="text-muted">Total Tagihan</span>
                                    </div>
                                    <div>
                                        <span>{{item.total_purchase+item.total_shipping_cost-item.total_coupon | formatRupiah}}</span>
                                    </div>
                                </b-col>
                                <b-col cols="4" class="text-truncate">
                                    <div>
                                        <span class="text-muted">Status Tagihan</span>
                                    </div>
                                    <template v-if="item.payment_status">
                                        <div>
                                        <span v-html="paymentStatusBadge(item.payment_status_id)"></span>
                                        </div>
                                        <div class="mt-1" v-if="item.expired_at">
                                            <span class="text-muted">Bayar sebelum </span><span>{{ item.expired_at | moment("dddd, Do MMMM YYYY, h:mm") }}</span>
                                        </div>
                                        <div v-if="item.payment_status_id == 2">
                                            <span class="text-muted"></span><span>{{ item.updated_at | moment("dddd, Do MMMM YYYY, H:mm") }}</span>
                                        </div>
                                        <div v-if="item.payment_status_id == 3">
                                            <span>{{ item.updated_at | moment("dddd, Do MMMM YYYY, h:mm") }}</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div v-html="paymentStatusBadge()"></div>
                                        <span class="text-muted">Bayar sebelum </span><span>{{ item.order.expired_at | moment("dddd, Do MMMM YYYY, H:mm") }}</span>
                                    </template>
                                </b-col>
                                <b-col cols="2" class="text-truncate">
                                    <div class="float-right" style="height: 63px; line-height: 63px;">
                                        <router-link :to="'/purchase/'+item.id">
                                        <button type="button" class="btn btn-outline-primary align-middle">Lihat Detail</button>
                                        </router-link>
                                    </div>
                                </b-col>
                            </b-row>
                        </div>
                        <b-row v-for="(data, index) in item.order.order_detail" :key="data.id">
                            <b-col cols="12" v-if="index != 0">
                                <hr>
                            </b-col>
                            <b-col cols="4" class="text-truncate">
                                <div class="text-muted">Produk</div>
                                <template  v-for="order_item in data.order_item">
                                    <div :key="order_item.id" class="pt-2">
                                        <span><img class="img-thumb" :src="order_item.product.photo.small" alt=""></span>
                                        <router-link class="product-click" :to="'/'+data.store.store_url+'/'+order_item.product.id+'/'+order_item.product.name|Slug">
                                        <strong class="pl-2 text-muted">{{order_item.product.name}}</strong>
                                        </router-link>
                                    </div>
                                </template>
                            </b-col>
                            <b-col cols="2" class="text-truncate">
                                <div>
                                    <span class="text-muted">Penjual</span>
                                </div>
                                <div>
                                    <span>{{data.store.name}}</span>
                                </div>
                                <div> 
                                    <router-link target="_blank" :to="'/message?to_store='+data.store_id+'&to_name='+data.store.name">
                                        <span class="text-muted mouse-pointer">Chat Penjual</span>
                                    </router-link>
                                </div>
                            </b-col>
                            <b-col cols="6" class="text-truncate">
                                <div>
                                    <span class="text-muted">Status Pembelian</span>
                                </div>
                                <div>
                                    <span v-html="orderStatusBadge(data.order_status_id)"></span>
                                </div>
                            </b-col>
                        </b-row>
                    </b-card>
                </template>
            </template>

            <div class="row">
                <div class="col-md-8">
                    <pagination :data="orders" :limit=3 v-on:pagination-change-page="getItems"></pagination>
                </div>
                <div class="col-md-4">
                    <div class="float-right">
                        <select name="pageLength" class="form-control" v-model="filterItemForm.pageLength" @change="getItems" v-if="orders.total">
                            <option value="5">5 per halaman</option>
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>
      </b-card>
    </div>
  </div>
</template>
<script>
import pagination from 'laravel-vue-pagination'
import helper from '../../services/helper'
export default {
  name: 'Payment',
  components: {
      pagination
  },
  data () {
    return {
        orders: {},
        page: '1',
        filterItemForm: {
            sortBy : 'date',
            order: 'desc',
            type : 'invoice',
            invoice_id : '',
            store : '',
            product : '',
            order_status : '',
            pageLength: 5
        },
        snapToken : ''
    }
  },
  mounted() {
    this.setUrlQuery();
    this.getItems();
  },
  methods: {
    getItems(page){
        if(!isNaN(page)){
            this.page = page
        }
        let url = helper.getFilterURL(this.filterItemForm);
        axios.get('/api/user/purchases'+'?page='+this.page+url)
        .then(res => {
            this.orders = res.data
            this.$router.push('/purchase?page='+this.page+url)
        })
        .catch(err => {
            console.error(err); 
        })
    },
    setUrlQuery(){
        if(this.$route.query.sortBy){
            this.filterItemForm.sortBy = this.$route.query.sortBy
        }
        if(this.$route.query.status){
            this.filterItemForm.status = this.$route.query.status;
        }
        if(this.$route.query.order){
            this.filterItemForm.order = this.$route.query.order
        }
        this.filterItemForm.invoice_id = this.$route.query.invoice_id,
        this.filterItemForm.store = this.$route.query.store,
        this.filterItemForm.product = this.$route.query.product;
        if(!isNaN(this.$route.query.page)){
            this.page = this.$route.query.page
        }
        console.log(21332)

    },
    makePayment(order_id){
        axios.get('/api/user/purchases/'+order_id+'/getToken')
        .then(res => {
            this.snapToken = res.data.token
            if(this.snapToken){
          snap.pay(this.snapToken);
        }
        })
        .catch(err => {
            console.error(err); 
        })
    },
    paymentStatusBadge: function (value) {
        if (value == 1) {
            return '<span class="badge badge-warning">Belum dibayar</span>'
        }else if (value == 2){
            return '<span class="badge badge-info">Menunggu pembayaran diterima</span>'
        }else if (value == 3){
            return '<span class="badge badge-success">Sudah dibayar</span>'
        }else if (value == 4){
            return '<span class="badge badge-dark">Kadaluarsa</span>'
        }else if (value == 5){
            return '<span class="badge badge-danger">Gagal</span>'
        }else if (value == 6){
            return '<span class="badge badge-danger">Dibatalkan</span>'
        }else if (value == 7){
            return '<span class="badge badge-danger">Dikembalikan</span>'
        }
    },
    orderStatusBadge: function (value) {
        if (!value) return '--'
        if (value == 1){
            return '<span class="badge badge-dark">Menunggu Pembayaran</span>'
        }if (value == 2){
            return '<span class="badge badge-info">Menunggu respon penjual</span>'
        }else if (value == 3){
            return '<span class="badge badge-info">Sedang diproses penjual</span>'
        }else if (value == 4){
            return '<span class="badge badge-primary">Sedang dikirim</span>'
        }else if (value == 5){
            return '<span class="badge badge-primary">Sudah Terkirim</span>'
        }else if (value == 6){
            return '<span class="badge badge-success">Selesai</span>'
        }else if (value == 10){
            return '<span class="badge badge-danger">Ditolak penjual</span>'
        }else if (value == 11){
            return '<span class="badge badge-danger">Dibatalkan pembeli</span>'
        }else if (value == 12){
            return '<span class="badge badge-danger">Kadaluarsa</span>'
        }else if (value == 13){
            return '<span class="badge badge-danger">Dikomplain</span>'
        }else if (value == 14){
            return '<span class="badge badge-danger">Dikembalikan</span>'
        }
    }
  },
  computed: {
    time: function(){
      return this.date.format('mm:ss');
    }
  },
  filters: {
    formatDate(date) {
        return helper.formatDate(date);
    },
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
    Slug(value){
      return helper.toSlug(value);
    }
  },
  watch: {
      '$route' (to, from) {
          this.setUrlQuery();
    },
  },
}
</script>