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
                                <th style="width: 35%">DATA ORDERAN</th>
                                <th style="width: 25%">PENGIRIMAN</th>
                                <th style="width: 25%">STATUS</th>
                                <th style="width: 15%">TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="item in orders.data">
                            <tr :key="item.id">
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
                                    <span class="badge badge-success">Selesai</span>
                                </td>
                                <td>
                                    <div class="text-center form-group">
                                        <div>
                                            <button v-on:click="showDetail(item.id)" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle">Detail</button>
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
    <detailModal :selectedOrder="selectedOrder"/>
  </div>
</template>
<script>
import detailModal from './modal/saleDetail'
import pagination from 'laravel-vue-pagination'
import helper from '../../services/helper'
export default {
  name: 'Payment',
  components: {
      pagination,
      detailModal
  },
  data () {
    return {
        orders: {},
        filterItemForm: {
            sortBy : 'id',
            status : 'success',
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
            console.log(res.data);
        })
        .catch(err => {
            console.error(err); 
        })
    },
    showDetail(order_id){
        axios.get('/api/user/seller/sales/'+order_id)
        .then(res => {
            this.selectedOrder = res.data;
        })
        .catch(err => {
            console.error(err); 
        })
    },
    saleStatusBadge: function () {
        return '<span><i class="fa fa-cog text-primary fa-lg" data-toggle="tooltip" title="Diproses Penjual"></i> --- <i class="fa fa-truck text-primary fa-flip-horizontal fa-lg" data-toggle="tooltip" title="Sedang Dikirim"></i> --- <i class="fa fa-dropbox fa-lg" data-toggle="tooltip" title="Sampai Tujuan"></i> --- <i class="fa fa-check-square-o fa-lg" data-toggle="tooltip" title="Transaksi Selesai"></i></span><div>Sedang Dikirim</div>'
    },
    truncateString(value,length){
        return helper.truncateString(value,length);
    },
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