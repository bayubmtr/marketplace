<template>
	<div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <b-card>
                    <div slot="header">
                        <strong>Produk</strong>
                    </div>

                        <div class="row m-t-40">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input class="form-control" v-model="filterProductForm.name" @blur="getProducts">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <input class="form-control" v-model="filterProductForm.category" @blur="getProducts">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Toko</label>
                                    <input class="form-control" v-model="filterProductForm.store" @blur="getProducts">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" v-model="filterProductForm.status" @change="getProducts">
                                        <option value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="2">inactive</option>
                                        <option value="3">Banned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterProductForm.sortBy" @change="getProducts">
                                        <option value="name">Product Name</option>
                                        <option value="price">Price</option>
                                        <option value="category">Category</option>
                                        <option value="store">Store</option>
                                        <option value="status">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Order</label>
                                    <select name="order" class="form-control" v-model="filterProductForm.order" @change="getProducts">
                                        <option value="asc">Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title">Daftar Produk</h4>
                        <h6 class="card-subtitle" v-if="products.data">Total {{products.total}} result found!</h6>
                        <h6 class="card-subtitle" v-else>No result found!</h6>
                        <div class="float-right mb-1">
                            <download-excel
                                :data="products.data"
                                :fields = "json_fields"
                                :name="'produk-'+today+'.xls'">
                                <button>Export ke Excel</button>
                            </download-excel>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered" v-if="products.total">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Terjual</th>
                                        <th>Berat</th>
                                        <th>Status</th>
                                        <th>Diperbarui</th>
                                        <th style="width:90px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products.data" :key="product.id">
                                        <td><router-link target="_blank" :to="'/'+product.store.store_url+'/'+product.id+'/'+product.name | slug">{{product.name}}</router-link></td>
                                        <td>{{product.category.name}}</td>
                                        <td>{{product.price|formatRupiah}}</td>
                                        <td><span v-if="product.product_statistic" v-text="product.product_statistic.sold_count"></span><span v-else>0</span></td>
                                        <td v-text="product.weight"></td>
                                        <td v-html="getProductStatus(product.product_status_id)"></td>
                                        <td>{{product.updated_at | moment}}</td>
                                        <td class="text-center">
                                            <div class="row align-center">
                                                <click-confirm yes-class="btn btn-success" no-class="btn btn-danger" :messages="{ title: 'Apakah Anda Yakin ?', yes: 'Ya', no: 'Batal' }">
                                                    <button class="btn btn-danger btn-sm ml-3" @click.prevent="deleteProduct(product.id)" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                                                    <button v-if="product.product_status_id == 1 || product.product_status_id == 2" class="btn btn-warning btn-sm"  @click.prevent="bannedProduct(product.id)" data-toggle="tooltip" title="Blokir"><i class="fa fa-times"></i></button>
                                                    <button v-if="product.product_status_id == 11" class="btn btn-success btn-sm"  @click.prevent="unbannedProduct(product.id)" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></button>
                                                </click-confirm>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-8">
                                    <pagination :data="products" :limit=3 v-on:pagination-change-page="getProducts"></pagination>
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <select name="pageLength" class="form-control" v-model="filterProductForm.pageLength" @change="getProducts" v-if="products.total">
                                            <option value="5">5 per page</option>
                                            <option value="10">10 per page</option>
                                            <option value="25">25 per page</option>
                                            <option value="100">100 per page</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-card>
                </div>
            </div>
        </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    import helper from '../../services/helper'
    import ClickConfirm from 'click-confirm'

    export default {
        name: 'modals',
        components : { pagination, ClickConfirm },
        data() {
            return {
                products: {},
                filterProductForm: {
                    sortBy : 'id',
                    order: 'desc',
                    name: '',
                    category: '',
                    store: '',
                    status: '',
                    pageLength: 5
                },
                excel : {},
                json_fields: {
                'ID Produk': 'id',
                'Nama Produk': 'name',
                'Pemilik' : 'store.name',
                'Kategori Produk': 'category.name',
                'Harga Produk': 'price',
                'Berat': 'weight',
                'Status': 'produk_status.name',
                'Stock': 'stock',
                'kondisi': 'condition',
                'Total Terjual': 'product_statistic.sold_count',
                'Dilihat': 'product_statistic.view_count',
                'Difavoritkan': 'product_statistic.favorite_count',
                'Dibuat': 'created_at',
                'Diperbarui': 'updated_at',
                },
            }
        },
        mounted() {
            this.getProducts();
        },
        computed: {
            today(){
                return Vue.moment().format("D-MMMM-YY.h-m");
            }
        },
        watch: {
            Category: function (Category) {
                this.getSubCategory(Category);
            },
            SubCategory: function (SubCategory) {
                this.getSubSubCategory(SubCategory);
            }
        },
        methods: {
            getProducts(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterProductForm);
                axios.get('/api/admin/products?page=' + page + url)
                    .then(response => this.products = response.data );
            },
            deleteProduct(id){
                axios.delete('/api/admin/products/'+id).then(response => {
                    toastr['success'](response.data.message);
                    this.getProducts();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            bannedProduct(id){
                axios.patch('/api/admin/products/'+id, {type : 'banned'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getProducts();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            unbannedProduct(id){
                axios.patch('/api/admin/products/'+id, {type : 'active'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getProducts();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            getProductStatus(status){
                if(status == '2')
                    return '<span class="badge badge-warning">Inactive</span>';
                else if(status == '1')
                    return '<span class="badge badge-success">Active</span>';
                else if(status == '3')
                    return '<span class="badge badge-danger">Deleted</span>';
                else if(status == '11')
                    return '<span class="badge badge-danger">Banned</span>';
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
        }
    }
</script>
