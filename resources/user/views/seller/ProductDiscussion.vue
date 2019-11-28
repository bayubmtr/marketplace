<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <div slot="header">
            <span class="lead">Daftar Diskusi Produk</span>
        </div>
        <h6 class="card-subtitle" v-if="products.total >= 1">Total : {{products.total}} diskusi belum dibalas</h6>
        <h6 class="card-subtitle" v-if="products.total == 0">Tidak Ada Data !</h6>
        <h6 class="card-subtitle" v-if="products.status == 204">Anda belum memiliki toko !</h6>
            <template v-if="products.total >= 1">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">
                                    <label class="form-checkbox">
                                        <input type="checkbox">
                                        <i class="form-icon"></i>
                                    </label>
                                </th>
                                <th style="width: 35%">NAMA PRODUK</th>
                                <th style="width: 35%">KATEGORI</th>
                                <th style="width: 35%">HARGA</th>
                                <th style="width: 10%">TERJUAL</th>
                                <th style="width: 10%">DILIHAT</th>
                                <th style="width: 10%">STOK</th>
                                <th style="width: 15%">TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(item, index) in products.data">
                            <tr :key="index" :class="checkProductStock(item.stock)">
                                <td>{{index+1}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.category}}</td>
                                <td>{{item.price}}</td>
                                <td>{{item.sold_count}}</td>
                                <td>{{item.view}}</td>
                                <td>{{item.stock}}</td>
                                <td class="text-center">
                                    <router-link :to="'/seller/product/'+item.id"><span class="fa fa-pencil-square-o fa-lg mouse-pointer" data-toggle="tooltip" title="Edit"></span></router-link>
                                    <span v-on:click="deleteProductConfirmation(item.id)" class="fa fa-times fa-lg mouse-pointer" data-toggle="tooltip" title="Hapus"></span>
                                    <span v-on:click="archiveProductConfirmation(item.id)" v-if="item.status == 'active'" class="fa fa-floppy-o fa-lg mouse-pointer" data-toggle="tooltip" title="Arsipkan"></span>
                                    <span v-on:click="sellProductConfirmation(item.id)" v-if="item.status == 'inactive'" class="fa fa-check fa-lg mouse-pointer" data-toggle="tooltip" title="Jual"></span>
                                </td>
                            </tr>
                            </template>
                        </tbody>
                    </table>
            </template>

            <div class="row">
                <div class="col-md-8">
                    <pagination :data="products" :limit=3 v-on:pagination-change-page="getProducts"></pagination>
                </div>
                <div class="col-md-4">
                    <div class="float-right">
                        <select name="pageLength" class="form-control" v-model="filterProductForm.pageLength" @change="getProducts" v-if="products.total">
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
  name: 'Product',
  components: {
      pagination,
  },
  data () {
    return {
        products: {},
        filterProductForm : {
            pageLength : 5,
            status : 'all',
        }
    }
  },
  mounted() {
      this.setUrlQuery();
      this.getProducts();
  },
  methods: {
    getProducts(page){
        if (typeof page === 'undefined') {
                    page = 1;
                }
        let url = helper.getFilterURL(this.filterProductForm);
        axios.get('/api/user/seller/products?page='+page+url)
        .then(res => {
            this.products = res.data
        })
        .catch(err => {
            console.error(err); 
        })
    },
    sellProductConfirmation(id){
        let options = {
        okText: 'Jual',
        cancelText: 'Batal',
        }
        this.$dialog
        .confirm('Masukan ke daftar produk yang dijual ?', options)
        .then(zzz => {
            this.sellProduct(id);
        }
        )
        .catch(function() {
            console.log('Clicked on cancel');
        });
    },
    sellProduct(id){
        axios.patch('/api/user/seller/products/'+id, {type : 'sell'})
        .then(res => {
            toastr.success('Berhasil Mengubah Status Produk');
            this.getProducts();
        })
        .catch(err => {
            console.error(err); 
        })
    },
    archiveProductConfirmation(id){
        let options = {
        okText: 'Arsipkan',
        cancelText: 'Batal',
        }
        this.$dialog
        .confirm('Masukan ke daftar Arsip produk ?', options)
        .then(zzz => {
            this.archiveProduct(id);
        }
        )
        .catch(function() {
            console.log('Clicked on cancel');
        });
    },
    archiveProduct(id){
        axios.patch('/api/user/seller/products/'+id, {type : 'archive'})
        .then(res => {
            toastr.success('Berhasil Mengubah Status Produk');
            this.getProducts();
        })
        .catch(err => {
            console.error(err); 
        })
    },
    deleteProductConfirmation(id){
        let options = {
        okText: 'Hapus Produk',
        cancelText: 'Batal',
        }
      this.$dialog
      .confirm('Hapus Produk Ini ?', options)
      .then(zzz => {
        this.deleteProduct(id);
      }
      )
      .catch(function() {
        console.log('Clicked on cancel');
      });
    },
    deleteProduct(id){
        axios.delete('/api/user/seller/products/'+id)
        .then(res => {
            toastr.success('Berhasil Menghapus Produk');
            this.getProducts();
        })
        .catch(err => {
            console.error(err); 
        })
    },
    showDetail(order_id, modal_id){
        axios.get('/api/user/sales/'+order_id)
        .then(res => {
            this.selectedOrder = res.data;
            this.showModal = modal_id
        })
        .catch(err => {
            console.error(err); 
        })
    },
    checkProductStock(data){
        if(data == 0 ){
            return 'table-danger'
        }else if(data < 5 ){
            return 'table-warning'
        }
    },
    setUrlQuery(){
        if(this.$route.query.status){
            this.filterProductForm.status = this.$route.query.status
        }else{
             this.filterProductForm.status = 'all'
        }
        if(this.$route.query.sortBy){
            this.filterProductForm.sortBy = this.$route.query.sortBy
        }
        if(this.$route.query.order){
            this.filterProductForm.order = this.$route.query.order
        }
        if(!isNaN(this.$route.query.page)){
            this.page = this.$route.query.page
        }
        this.getProducts()
    },
  },
  computed: {
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
  },
}
</script>