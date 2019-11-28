<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Daftar Tagihan Anda</span>
        </div>
        <h6 class="card-subtitle" v-if="orders.total > 0">Anda memiliki {{orders.total}} tagihan</h6>
        <h6 class="card-subtitle" v-else>Anda tidak memilik tagihan</h6>
        <template v-if="orders.data">
                <template v-for="item in orders.data">
                    <b-card :key="item.id" class="mt-2">
                        <div slot="header">
                            <b-row>
                                <b-col cols="4" class="text-truncate">
                                    <div class="text-muted">No. Tagihan</div>
                                    <div><router-link :to="'/purchase/'+item.id">
                                        <strong class="mouse-pointer">{{item.id}}</strong>
                                        </router-link>
                                    </div>
                                    <small class="text-muted">{{ item.created_at | moment("Do MMMM YYYY, H:mm") }}</small>
                                </b-col>
                                <b-col cols="2" class="text-truncate">
                                    <div>
                                        <span class="text-muted">Total Tagihan</span>
                                    </div>
                                    <div>
                                        <span>{{item.total_payment | formatRupiah}}</span>
                                    </div>
                                </b-col>
                                <b-col cols="4" class="text-truncate">
                                    <div>
                                        <span class="text-muted">Status Tagihan</span>
                                    </div>
                                    <template v-if="item.payment">
                                        <div v-if="item.payment.payment_status_id == 2">
                                            <span v-if="item.payment.payment_status_id == 2" class="badge badge-warning">Menunggu pembayaran diterima</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <span class="badge badge-warning">Belum dibayar</span>
                                    </template>
                                    <div>
                                        <span class="text-muted">Bayar sebelum </span><span>{{ item.order.expired_at | moment("Do MMMM YYYY, H:mm") }}</span>
                                    </div>
                                </b-col>
                                <b-col cols="2" class="text-truncate">
                                    <div class="float-right form-group">
                                        <router-link :to="'/purchase/'+item.id">
                                            <button type="button" class="btn btn-block btn-outline-primary btn-sm align-middle">Lihat Detail</button>
                                        </router-link>
                                        <div v-if="item.payment == null">
                                            <button v-on:click="makePayment(item.id)" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle mt-2">Bayar sekarang</button>
                                        </div>
                                        <div v-if="item.payment == null">
                                            <button v-on:click="cancelOrder(item.id)" type="button" class="btn btn-block btn-outline-danger btn-sm align-middle mt-2">Batalkan</button>
                                        </div>
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
                                    <span class="text-muted">Chat Penjual</span>
                                </div>
                            </b-col>
                            <b-col cols="6" class="text-truncate">
                                <div>
                                    <span class="text-muted">Status Pembelian</span>
                                </div>
                                <div>
                                    <span>Menunggu Pembayaran</span>
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
    <script2 type="text/javascript"
                src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="SB-Mid-client-M-JamS73Cz1P7rj2">
    </script2>
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
        filterItemForm: {
            sortBy : 'date',
            order: 'desc',
            type :  'invoice',
            order_status : 'Payment',
            pageLength: 5
        },
        snapToken : ''
    }
  },
  mounted() {
      this.getItems();
  },
  methods: {
    getItems(page){
        if (typeof page === 'undefined') {
            page = 1;
        }
        let url = helper.getFilterURL(this.filterItemForm);
        axios.get('/api/user/purchases'+'?page='+page+url)
        .then(res => {
            this.orders = res.data
        })
        .catch(err => {
            console.error(err); 
        })
    },
    makePayment(invoice_id){
        axios.get('/api/user/payments?getToken='+invoice_id)
        .then(res => {
            this.snapToken = res.data
            if(this.snapToken){
          snap.pay(this.snapToken);
        }
        })
        .catch(err => {
            console.error(err); 
        })
    },
    cancelOrder(order_id){
        axios.patch('/api/user/purchases/'+order_id, {cancelInvoice : true})
        .then(res => {
            this.getItems();
        })
        .catch(err => {
            console.error(err); 
        })
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
  }
}
</script>