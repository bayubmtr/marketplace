<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Transaksi Penjualan</span>
        </div>
        <div class="row m-t-40">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Id Transaksi</label>
                    <input class="form-control" v-model="filterItemForm.order_id" @blur="getItems">
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
                    <label for="">Produk</label>
                    <input class="form-control" v-model="filterItemForm.product" @blur="getItems">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control" v-model="filterItemForm.status" @change="getItems">
                        <option value="all">Semua</option>
                        <option value="new">Pesanan Baru</option>
                        <option value="process">Diproses</option>
                        <option value="shipment">Dikirim</option>
                        <option value="delivered">Terkirim</option>
                        <option value="success">Selesai</option>
                        <option value="reject">Ditolak</option>
                        <option value="return">Dikembalikan</option>
                        <option value="complaint">Dikomplain</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Kelompokkan</label>
                    <select name="sortBy" class="form-control" v-model="filterItemForm.sortBy" @change="getItems">
                        <option value="id">ID Order</option>
                        <option value="date">Tanggal</option>
                        <option value="buyer">Pembeli</option>
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
        <h6 class="card-subtitle" v-if="orders.total >= 1">Total : {{orders.total}} transaksi</h6>
        <h6 class="card-subtitle" v-if="orders.total == 0">Tidak Ada Data !</h6>
        <h6 class="card-subtitle" v-if="orders.status == 204">Anda belum memiliki toko !</h6>
            <template v-if="orders.total >= 1">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    <label class="form-checkbox">
                                        <input type="checkbox" v-model="selectAll" @click="select">
                                        <i class="form-icon"></i>
                                    </label>
                                </th>
                                <th style="width: 35%">DATA ORDERAN</th>
                                <th style="width: 35%">PENGIRIMAN</th>
                                <th style="width: 10%">STATUS</th>
                                <th style="width: 15%">TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="item in orders.data">
                            <tr :key="item.id">
                                <td style="width: 5%">
                                    <label class="form-checkbox">
                                        <input type="checkbox" :value="item.id" v-model="selected">
                                        <i class="form-icon"></i>
                                    </label>
                                </td>
                                <td class="text-truncate" style="width: 35%">{{item.id}} ({{item.order.user.first_name}} {{item.order.user.last_name}})
                                <div v-for="(data, index) in item.order_item" :key="data.id">
                                    <div data-toggle="tooltip" :title="data.product.name">{{index+1}}. {{truncateString(data.product.name, 20)}} x {{data.quantity}}</div>
                                </div>
                                </td>
                                <td style="width: 35%">
                                    <span>{{item.order_shipment_detail.logistic.name}} - {{item.order_shipment_detail.logistic_service}}</span>
                                    <div>
                                        <small>{{item.order.order_shipment.address_village.address_district.address_city.name}}, 
                                            {{item.order.order_shipment.address_village.address_district.address_city.address_province.name}}
                                        </small>
                                    </div>
                                </td>
                                <td style="width: 10%">
                                    <span v-html="saleStatusBadge(item.order_status_id)"></span>
                                </td>
                                <td style="width: 15%">
                                    <div class="text-center form-group mb-0">
                                        <div>
                                            <button @click="showDetail(item.id, 'saleDetail')" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle">Detail</button>
                                        </div>
                                        <div v-if="item.order_status_id == 4">
                                            <button v-on:click="cekResi(item.order_shipment_detail.tracking_number)" type="button" class="btn btn-block btn-outline-primary btn-sm align-middle mt-2">Lacak</button>
                                        </div>
                                        <div v-if="item.order_status_id == 2">
                                            <button v-on:click="acceptOrder(item.id)" type="button" class="btn btn-block btn-outline-info btn-sm align-middle mt-2">Terima</button>
                                        </div>
                                        <div v-if="item.order_status_id == 2">
                                            <button v-on:click="rejectOrder(item.id)" type="button" class="btn btn-block btn-outline-danger btn-sm align-middle mt-2">Tolak</button>
                                        </div>
                                        <div v-if="item.order_status_id == 3">
                                            <button v-on:click="sendOrder(item.id)" type="button" class="btn btn-block btn-outline-info btn-sm align-middle mt-2">Kirim</button>
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
    <saleReject :order_id="selectedOrderId" v-on:getOrder="getItems(page)"/>
    <saleSend :order_id="selectedOrderId" v-on:getOrder="getItems(page)"/>
  </div>
</template>
<script>
import pagination from 'laravel-vue-pagination'
import saleDetail from './modal/saleDetail'
import saleReject from './modal/saleReject'
import saleSend from './modal/saleSend'
import helper from '../../services/helper'
export default {
  name: 'Sale',
  components: {
      pagination,
      saleDetail,
      saleReject,
      saleSend
  },
  data () {
    return {
        orders: {},
        page: '1',
        filterItemForm: {
            sortBy : 'id',
            order: 'desc',
            type : 'invoice',
            buyer : '',
            product : '',
            status : '',
            pageLength: 5
        },
        selected: [],
        selectAll: false,
        showModal : '',
        selectedOrder : {},
        selectedOrderId : ''
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
        axios.get('/api/user/seller/sales'+'?page='+this.page+url)
        .then(res => {
            this.orders = res.data
            this.$router.push('/sale?page='+this.page+url)
        })
        .catch(err => {
            console.error(err); 
        })
    },
    showDetail(order_id, modal_id){
        axios.get('/api/user/seller/sales/'+order_id)
        .then(res => {
            this.selectedOrder = res.data;
            this.showModal = modal_id
        })
        .catch(err => {
            console.error(err); 
        })
    },
    select() {
			this.selected = [];
			if (!this.selectAll) {
				for (let i in this.orders.data) {
					this.selected.push(this.orders.data[i].id);
				}
			}
    },
    setUrlQuery(){
        this.filterItemForm.sortBy = this.$route.query.sortBy,
        this.filterItemForm.order = this.$route.query.order,
        this.filterItemForm.order_id = this.$route.query.order_id,
        this.filterItemForm.store = this.$route.query.store,
        this.filterItemForm.product = this.$route.query.product,
        this.filterItemForm.status = this.$route.query.status;
        if(!isNaN(this.$route.query.page)){
            this.page = this.$route.query.page
        }

    },
    saleStatusBadge: function (value) {
        if (!value) return '--'
        if (value == 2){
            return '<span class="badge badge-warning">Pesanan Baru</span>'
        }else if (value == 3){
            return '<span class="badge badge-primary">Sedang Diproses</span>'
        }else if (value == 4){
            return '<span class="badge badge-primary">Sedang Dikirim</span>'
        }else if (value == 5){
            return '<span class="badge badge-success">Sudah Sampai</span>'
        }else if (value == 6){
            return '<span class="badge badge-success">Selesai</span>'
        }else if (value == 10){
            return '<span class="badge badge-danger">Ditolak</span>'
        }else if (value == 13){
            return '<span class="badge badge-danger">Dikomplain</span>'
        }else if (value == 14){
            return '<span class="badge badge-danger">Dikembalikan</span>'
        }
    },
    truncateString(value,length){
        return helper.truncateString(value,length);
    },
    acceptOrder(id){
        helper.acceptOrder(id);
        this.getItems(this.page);
    },
    sendOrder(id){
        this.selectedOrderId = id
        this.$bvModal.show('saleSend');
    },
    rejectOrder(id){
        this.selectedOrderId = id
        this.$bvModal.show('saleReject');
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
    Slug(value){
      return helper.toSlug(value);
    },
    
  },
  watch: {
      '$route' (to, from) {
          this.setUrlQuery();
    },
    selectedOrder(){
         this.$bvModal.show(this.showModal);
    }
  },
}
</script>