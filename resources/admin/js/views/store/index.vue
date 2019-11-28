<template>
	<div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <b-card>
                    <div slot="header">
                        <strong>Store</strong>
                    </div>

                        <div class="row m-t-40">
                             <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Nama Toko</label>
                                    <input class="form-control" v-model="filterUserForm.name" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Nama Pemilik</label>
                                    <input class="form-control" v-model="filterUserForm.owner" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">URL Toko</label>
                                    <input class="form-control" v-model="filterUserForm.store_url" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" v-model="filterUserForm.status" @change="getUsers">
                                        <option value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterUserForm.sortBy" @change="getUsers">
                                        <option value="name">Nama Toko</option>
                                        <option value="store_url">URL Toko</option>
                                        <option value="status">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Order</label>
                                    <select name="order" class="form-control" v-model="filterUserForm.order" @change="getUsers">
                                        <option value="asc">Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title">Daftar User</h4>
                        <h6 class="card-subtitle" v-if="users.data">Total {{users.total}} ditemukan !</h6>
                        <h6 class="card-subtitle" v-else>Tidak ada data !</h6>
                        <div class="float-right mb-1">
                            <download-excel
                                :data="users.data"
                                :fields = "json_fields"
                                :name="'store-'+today+'.xls'">
                                <button>Export ke Excel</button>
                            </download-excel>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered mt-2" v-if="users.total">
                                <thead>
                                    <tr>
                                        <th>Nama Toko</th>
                                        <th>Pemilik</th>
                                        <th>URL Toko</th>
                                        <th>Jumlah Penjualan</th>
                                        <th>Jumlah Produk</th>
                                        <th>Jumlah Penilaian</th>
                                        <th>Rata-rata Penilaian</th>
                                        <th>Status</th>
                                        <th style="width:50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td v-text="user.name"></td>
                                        <td v-text="user.user_profile.first_name+' '+user.user_profile.last_name"></td>
                                        <td v-text="user.store_url"></td>
                                        <td v-text="user.store_statistic.sold_count"></td>
                                        <td v-text="user.store_statistic.product_count"></td>
                                        <td v-text="user.store_statistic.review_count"></td>
                                        <td v-text="user.store_statistic.review_avg"></td>
                                        <td v-html="getStoreStatus(user.is_active)"></td>
                                        <td>
                                            <click-confirm yes-class="btn btn-success" no-class="btn btn-danger" :messages="{ title: 'Apakah Anda Yakin ?', yes: 'Ya', no: 'Batal' }">
                                                <button v-if="user.is_active == 1" class="btn btn-warning btn-sm" @click.prevent="bannedUser(user.id)" data-toggle="tooltip" title="Nonaktifkan"><i class="fa fa-times"></i></button>
                                                <button v-if="user.is_active == 0" class="btn btn-success btn-sm" @click.prevent="unbannedUser(user.id)" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></button>
                                            </click-confirm>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-8">
                                    <pagination :data="users" :limit=3 v-on:pagination-change-page="getUsers"></pagination>
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <select name="pageLength" class="form-control" v-model="filterUserForm.pageLength" @change="getUsers" v-if="users.total">
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
        components : { pagination, ClickConfirm },
        data() {
            return {
                users: {},
                filterUserForm: {
                    sortBy : 'id',
                    order: 'desc',
                    status: '',
                    title: '',
                    name : '',
                    owner : '',
                    pageLength: 5
                },
                json_fields: {
                    'ID': 'id',
                    'Nama Toko': 'name',
                    'URL Toko': 'store_url',
                    'Pemilik' : 'user.first_name',
                    'Jumlah Penjualan' : 'store_statistic.sold_count',
                    'Jumlah Produk': 'store_statistic.product_count',
                    'Jumlah Penilaian': 'store_statistic.review_count',
                    'Rata-Rata Penilaian': 'store_statistic.review_avg',
                    'Dibuat': 'created_at',
                    'Diperbarui': 'updated_at',
                },
            }
        },
        mounted() {
            this.getUsers();
        },
        computed: {
            today(){
                return Vue.moment().format("D-MMMM-YY.h-m");
            }
        },
        methods: {
            getUsers(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterUserForm);
                axios.get('/api/admin/stores?page=' + page + url)
                    .then(response => this.users = response.data );
            },
            bannedUser(id){
                axios.patch('/api/admin/stores/'+id, {type : 'inactive'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getUsers();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            unbannedUser(id){
                axios.patch('/api/admin/stores/'+id, {type : 'active'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getUsers();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            getStoreStatus(user){
                if(user == '1')
                    return '<span class="badge badge-success">Active</span>';
                else if(user == '0')
                    return '<span class="badge badge-danger">Inactive</span>';
                else
                    return;
            }
        },
        filters: {
            
            ucword(value) {
                return helper.ucword(value);
            },
            gender(value){
                return helper.gender(value);
            }
        }
    }
</script>
