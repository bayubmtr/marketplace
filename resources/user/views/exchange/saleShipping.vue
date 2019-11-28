<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Pengiriman Barang</span>
        </div>
        <h6 class="card-subtitle" v-if="orders.total >= 1">Total : {{orders.total}} transaksi</h6>
        <h6 class="card-subtitle" v-if="orders.total == 0">Tidak Ada Data !</h6>
        <h6 class="card-subtitle" v-if="orders.status == 204">Anda belum memiliki toko !</h6>
            <template v-if="orders.total >= 1">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 35%">DATA ORDERAN</th>
                                <th style="width: 25%">PENGIRIMAN</th>
                                <th style="width: 20%">STATUS</th>
                                <th style="width: 15%">TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(item, index) in orders.data">
                            <tr :key="item.id">
                                <td>{{index+1}}</td>
                                <td>{{item.id}}
                                <div v-for="data in item.order_item" :key="data.id" class="text-truncate">
                                    <div data-toggle="tooltip" :title="data.product.name">{{truncateString(data.product.name, 20)}}</div>
                                </div>
                                </td>
                                <td>
                                    <div>
                                        <span>{{item.order_shipment_detail.logistic.name}} - {{item.order_shipment_detail.logistic_service}}</span>
                                    <div>
                                        <small>{{item.order.order_shipment.address_village.address_district.address_city.name}}, 
                                            {{item.order.order_shipment.address_village.address_district.address_city.address_province.name}}
                                        </small>
                                    </div>
                                    <span>No Resi : <span v-on:click="cekResi(item.order_shipment_detail.tracking_number)" class="mouse-pointer">{{item.order_shipment_detail.tracking_number}}</span></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary" v-if="item.order_status_id == 4">Sedang Dikirim</span>
                                    <span class="badge badge-success" v-else>Sudah Sampai</span>
                                </td>
                                <td>
                                    <div class="text-center form-group">
                                        <div>
                                            <button v-on:click="showDetail(item.id)" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle">Lihat Detail</button>
                                        </div>
                                        <div>
                                            <button v-on:click="cekResi(item.order_shipment_detail.tracking_number)" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle mt-2">Lacak</button>
                                        </div>
                                        <div v-if="item.oreder_status_id == 5">
                                            <button type="button" class="btn btn-block btn-outline-primary btn-sm align-middle mt-2">Chat Pembeli</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </template>
                        </tbody>
                    </table>
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
    <saleDetail :selectedOrder="selectedOrder"/>
  </div>
</template>
<script>
import saleDetail from './modal/saleDetail'
import pagination from 'laravel-vue-pagination'
import helper from '../../services/helper'
export default {
  name: 'saleShipping',
  components: {
      pagination,
      saleDetail
  },
  data () {
    return {
        orders: {},
        filterItemForm: {
            sortBy : 'id',
            status : 'shipping',
            order: 'desc',
            pageLength: 5
        },
        selectedOrder : {}
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
        axios.get('/api/user/seller/sales'+'?page='+page+url)
        .then(res => {
            this.orders = res.data
        })
        .catch(err => {
            console.error(err); 
        })
    },
    showDetail(order_id){
        axios.get('/api/user/seller/sales/'+order_id)
        .then(res => {
            this.selectedOrder = res.data;
            this.showModal = 'saleDetail'
        })
        .catch(err => {
            console.error(err); 
        })
    },
    truncateString(value,length){
        return helper.truncateString(value,length);
    },
    cekResi: function (resi) {   
          window.open("https://cekresi.com/?v=wdg&noresi="+resi, "_blank");    
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
  }
}
</script>