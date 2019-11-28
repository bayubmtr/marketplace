<template>
	<div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <b-card>
                    <div slot="header">
                        <strong>User</strong>
                    </div>

                        <div class="row m-t-40">
                             <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">ID User</label>
                                    <input class="form-control" v-model="filterUserForm.id" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Nama User</label>
                                    <input class="form-control" v-model="filterUserForm.name" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input class="form-control" v-model="filterUserForm.email" @blur="getUsers">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" v-model="filterUserForm.status" @change="getUsers">
                                        <option value="">All</option>
                                        <option value="1">Pending Activation</option>
                                        <option value="2">Activated</option>
                                        <option value="11">Banned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterUserForm.sortBy" @change="getUsers">
                                        <option value="first_name">Nama Usere</option>
                                        <option value="id">ID User</option>
                                        <option value="email">Email</option>
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
                                :name="'user-'+today+'.xls'">
                                <button>Export ke Excel</button>
                            </download-excel>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered mt-2" v-if="users.total">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Lahir</th>
                                        <th>JK</th>
                                        <th>Email</th>
                                        <th>Total Transaksi</th>
                                        <th>Peran</th>
                                        <th>Status</th>
                                        <th style="width:90px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td>{{user.profile.first_name}} {{user.profile.last_name}}</td>
                                        <td>{{ user.profile.date_of_birth}}</td>
                                        <td>{{ user.profile.gender | gender}}</td>
                                        <td v-text="user.email"></td>
                                        <td v-text="user.order_count"></td>
                                        <td v-text="user.user_role.name"></td>
                                        <td v-html="getUserStatus(user.user_status_id)"></td>
                                        <td>
                                            <click-confirm yes-class="btn btn-success" no-class="btn btn-danger" :messages="{ title: 'Apakah Anda Yakin ?', yes: 'Ya', no: 'Batal' }">
                                                <button class="btn btn-danger btn-sm" @click.prevent="deleteUser(user.id)" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                                                <button v-if="user.user_status_id == 2 || user.user_status_id == 1" class="btn btn-warning btn-sm" @click.prevent="bannedUser(user.id)" data-toggle="tooltip" title="Blokir"><i class="fa fa-times"></i></button>
                                                <button v-if="user.user_status_id == 11" class="btn btn-success btn-sm" @click.prevent="unbannedUser(user.id)" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></button>
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
                    pageLength: 5
                },
                json_fields: {
                    'ID': 'id',
                    'Nama Depan': 'profile.first_name',
                    'Nama Belakang': 'profile.last_name',
                    'Email' : 'email',
                    'Jenis Kelamin' : 'profile.gender',
                    'status': 'user_status.name',
                    'Peran': 'user_role.name',
                    'Total Belanja': 'order_count',
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
                axios.get('/api/admin/users?page=' + page + url)
                    .then(response => this.users = response.data );
            },
            deleteUser(id){
                axios.delete('/api/admin/users/'+id).then(response => {
                    toastr['success'](response.data.message);
                    this.getUsers();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            bannedUser(id){
                axios.patch('/api/admin/users/'+id, {type : 'banned'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getUsers();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            unbannedUser(id){
                axios.patch('/api/admin/users/'+id, {type : 'active'}).then(response => {
                    toastr['success'](response.data.message);
                    this.getUsers();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            getUserStatus(user){
                if(user == '1')
                    return '<span class="badge badge-warning">Not Verified</span>';
                else if(user == '2')
                    return '<span class="badge badge-success">Verified</span>';
                else if(user == '11')
                    return '<span class="badge badge-danger">Banned</span>';
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
