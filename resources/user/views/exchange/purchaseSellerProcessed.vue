<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Diproses Penjual</span>
        </div>
        <h6 class="card-subtitle" v-if="orders.total > 0">Anda memiliki {{orders.total}} transaksi</h6>
        <h6 class="card-subtitle" v-else>Anda tidak memilik transaksi</h6>
        <template v-if="orders.data">
            <template v-for="item in orders.data">
                <b-card :key="item.id" class="mt-2">
                    <div slot="header">
                        <b-row>
                            <b-col cols="4" class="text-truncate">
                                <div class="text-muted">No. Pembelian</div>
                                <div><router-link :to="'/purchase/'+item.id">
                                    <strong class="mouse-pointer">{{item.id}}</strong>
                                    </router-link>
                                </div>
                                <small class="text-muted">{{ item.order.created_at | moment("Do MMMM YYYY, H:mm") }}</small>
                            </b-col>
                            <b-col cols="2" class="text-truncate">
                                <div>
                                    <span class="text-muted">Total Pembelian</span>
                                </div>
                                <div>
                                    <span>{{item.sub_total_price | formatRupiah}}</span>
                                </div>
                            </b-col>
                            <b-col cols="4" class="text-truncate">
                                <div>
                                    <span class="text-muted">Status Pembelian</span>
                                </div>
                                    <span class="badge badge-warning" v-if="item.order_status_id == 2">Menunggu Respon Penjual</span>
                                    <span class="badge badge-primary" v-if="item.order_status_id == 3">Sedang Di Proses</span>
                            </b-col>
                            <b-col cols="2" class="text-truncate">
                                <div class="float-right" style="height: 63px; line-height: 63px;">
                                    <router-link :to="'/purchase/'+item.order.order_payment.id">
                                    <button type="button" class="btn btn-outline-primary align-middle">Lihat Detail</button>
                                    </router-link>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                    <b-row>
                        <b-col cols="4" class="text-truncate">
                            <div class="text-muted">Produk</div>
                            <template  v-for="order_item in item.order_item">
                                <div :key="order_item.id" class="pt-2">
                                    <span><img class="img-thumb" :src="order_item.product.photo.small" alt=""></span>
                                    <router-link class="product-click" :to="'/'+item.store.store_url+'/'+order_item.product.id+'/'+order_item.product.name|Slug">
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
                                <span>{{item.store.name}}</span>
                            </div>
                            <div>
                                <span class="text-muted">Chat Penjual</span>
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
        filterItemForm: {
            sortBy : 'id',
            order: 'desc',
            status : 'process',
            type : 'order',
            order_status : 'seller-processed',
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
    },
    
  },
  computed: {
    time: function(){
      return this.date.format('mm:ss');
    },
  },
  filters: {
    formatDate(date) {
        return helper.formatDate(date);
    },
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
    getTotalPurchase : function(value){
        let total = [];

        Object.entries(value).forEach(([key, val]) => {
            total.push(val.sub_total_price) // the value of the current key.
        });

        return total.reduce(function(total, num){ return total + num }, 0);
    },
    Slug(value){
      return helper.toSlug(value);
    }
  }
}
</script>