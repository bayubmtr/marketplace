<template>
	<div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <b-card>
                    <div slot="header">
                        <strong>Withdraw</strong>
                    </div>

                        <div class="row m-t-40">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nama User</label>
                                    <input class="form-control" v-model="filterWithdrawForm.name" @blur="getWithdraws">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nama Rekening</label>
                                    <input class="form-control" v-model="filterWithdrawForm.account_name" @blur="getWithdraws">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control" v-model="filterWithdrawForm.status" @change="getWithdraws">
                                        <option value="">All</option>
                                        <option value="1">Request</option>
                                        <option value="2">Accept</option>
                                        <option value="3">Reject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterWithdrawForm.sortBy" @change="getWithdraws">
                                        <option value="id">ID</option>
                                        <option value="created_at">Date</option>
                                        <option value="withdraw_status_id">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Order</label>
                                    <select name="order" class="form-control" v-model="filterWithdrawForm.order" @change="getWithdraws">
                                        <option value="asc">Asc</option>
                                        <option value="desc">Desc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h6 class="card-subtitle" v-if="withdraws.data">Total {{withdraws.total}} data ditemukan !</h6>
                        <h6 class="card-subtitle" v-else>Tidak ada data!</h6>
                        <div class="float-right mb-1">
                            <download-excel
                                :data="withdraws.data"
                                :fields = "json_fields"
                                :name="'withdraw-'+today+'.xls'">
                                <button>Export ke Excel</button>
                            </download-excel>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered" v-if="withdraws.total">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Bank</th>
                                        <th>Nama Rekening</th>
                                        <th>Nomor Rekening</th>
                                        <th>Jumlah Penarikan</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Diperbarui</th>
                                        <th style="width:50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="withdraw in withdraws.data" :key="withdraw.id">
                                        <td>{{withdraw.id}}</td>
                                        <td>{{withdraw.user_profile.first_name}} {{withdraw.user_profile.last_name}}</td>
                                        <td>{{withdraw.user_bank.bank_name}}</td>
                                        <td>{{withdraw.user_bank.account_name}}</td>
                                        <td>{{withdraw.user_bank.account_number}}</td>
                                        <td>{{withdraw.amount | formatRupiah}}</td>
                                        <td>
                                            <span v-if="withdraw.withdraw_status_id == 1" class="badge badge-warning">Diminta</span>
                                            <span v-else-if="withdraw.withdraw_status_id == 2" class="badge badge-success">Diterima</span>
                                            <span v-else-if="withdraw.withdraw_status_id == 3" class="badge badge-danger">Ditolak</span>
                                        </td>
                                        <td> {{withdraw.created_at | formatDate}}</td>
                                        <td> {{withdraw.updated_at | formatDate}}</td>
                                        <td class="text-center">
                                            <div class="row align-center">
                                                 <button class="btn btn-success btn-sm ml-3" @click.prevent="editWithdraw(withdraw.id)" data-toggle="tooltip" title="Ubah"><i class="fa fa-pencil"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-8">
                                    <pagination :data="withdraws" :limit=3 v-on:pagination-change-page="getWithdraws"></pagination>
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <select name="pageLength" class="form-control" v-model="filterWithdrawForm.pageLength" @change="getWithdraws" v-if="withdraws.total">
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
            <b-modal id="changeStatus" title="Ubah Status Withdraw" no-close-on-backdrop hide-footer centered>
            <b-row>
                <b-col cols="3">
                    Status
                </b-col>
                <b-col cols="9">
                    <select v-model="status" class="form-control">
                        <option :value="1">Request</option>
                        <option :value="2">Accept</option>
                        <option :value="3">Reject</option>
                    </select>
                    <b-button v-on:click="updateWithdraw()" class="float-right mt-2" variant="outline-primary">Ubah Status</b-button>
                </b-col>
            </b-row>
        </b-modal>
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
               withdraws: {},
               withdraw_id : '',
               status : '',
                filterWithdrawForm: {
                    sortBy : 'id',
                    order: 'asc',
                    name: '',
                    account_name : '',
                    status: '',
                    pageLength: 5
                },
                excel : {},
                json_fields: {
                'ID Withdraw': 'id',
                'Nama User': 'user_profile.first_name',
                'Nama Bank' : 'user_bank.bank_name',
                'Nama Rekening' : 'user_bank.account_name',
                'Nomor Rekening' : 'user_bank.account_number',
                'Jumlah': 'amount',
                'status': 'withdraw_status.name',
                'Description': 'description',
                },
            }
        },
        mounted() {
            this.getWithdraws();
        },
        computed: {
            today(){
                return this.$moment().format("D-MMMM-YY.h-m");
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
            getWithdraws(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterWithdrawForm);
                axios.get('/api/admin/withdraws?page=' + page + url)
                    .then(response => this.withdraws = response.data );
            },
            editWithdraw(id){
                this.withdraw_id = id;
                this.$bvModal.show('changeStatus');
            },
            updateWithdraw(id){
                axios.patch('/api/admin/withdraws/'+this.withdraw_id, {status : this.status}).then(response => {
                    toastr['success'](response.data.message);
                    this.getWithdraws();
                    this.$bvModal.hide('changeStatus');
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            getWithdrawStatus(status){
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
            formatDate(date) {
                return helper.formatDate(date);
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
