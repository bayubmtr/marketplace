<template>
	<div>
    <div class="row page-titles mt-3">
      <div class="col-md-6 col-8 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
            <li class="breadcrumb-item active">Admin</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Daftar Admin</h4>
              <h6 class="card-subtitle" v-if="items">Total {{items.total}} result found!</h6>
              <h6 class="card-subtitle" v-else>No result found!</h6>
              <div class="float-right mb-1">
                <button v-b-modal.addLogistic>Tambah</button>
            </div>
              <div class="table-responsive">
                <table class="table table-hover table-bordered" v-if="items.total">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Aktivitas Terakhir</th>
                      <th>status</th>
                      <th style="width:150px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in items.data" :key="item.id">
                      <td v-text="item.profile.first_name+' '+item.profile.last_name"></td>
                      <td v-text="item.email"></td>
                      <td v-text="item.last_activity"></td>
                      <td v-html="getItemStatus(item.user_status_id)"></td>
                      <td>
                        <click-confirm yes-class="btn btn-success" no-class="btn btn-danger" :messages="{ title: 'Apakah Anda Yakin ?', yes: 'Ya', no: 'Batal' }">
                            <button class="btn btn-danger btn-sm ml-3" @click.prevent="deleteItem(item.id)" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                            <button v-if="item.user_status_id == 2" class="btn btn-warning btn-sm"  @click.prevent="inactiveItem(item.id)" data-toggle="tooltip" title="Nonaktifkan"><i class="fa fa-times"></i></button>
                            <button v-if="item.user_status_id == 11" class="btn btn-success btn-sm"  @click.prevent="activeItem(item.id)" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></button>
                        </click-confirm>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="addLogistic" title="Tambah Admin" no-close-on-backdrop hide-footer centered>
            <b-row>
               <b-col cols="3">
                    Email
              </b-col>
              <b-col cols="9">
                  <input type="text" class="form-control" v-model="email">
                  <b-button v-on:click="createItem()" class="float-right mt-2" variant="outline-primary">Tambah</b-button>
              </b-col>
            </b-row>
        </b-modal>
  </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    import helper from '../../../services/helper'
    import ClickConfirm from 'click-confirm'

    export default {
        components : { pagination, ClickConfirm },
        data() {
            return {
                items: {},
                email : '',
            }
        },
        mounted() {
            this.getItems();
        },
        methods: {
            getItems() {
                axios.get('/api/admin/web/admins')
                .then(res => {
                  this.items = res.data
                })
                .catch(err => {
                  console.error(err); 
                })
            },
            getItemStatus(item){
                if(item == 2)
                    return '<span class="badge badge-success">Activated</span>';
                else if(item == 11)
                    return '<span class="badge badge-danger">Inactive</span>';
                else
                    return;
            },
            deleteItem(id){
              axios.delete('/api/admin/web/admins/'+id)
              .then(res => {
                toastr.success('Berhasil Menghapus Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
            createItem(){
              axios.post('/api/admin/web/admins', {email : this.email})
              .then(res => {
                toastr.success('Berhasil Menambah Admin')
                this.getItems()
                this.$bvModal.hide('addLogistic');
              })
              .catch(err => {
                toastr.error('Email tidak ditemukan')
                this.getItems()
                this.$bvModal.hide('addLogistic');
              })
            },
            activeItem(id){
              axios.patch('/api/admin/web/admins/'+id, {type : 'active'})
              .then(res => {
                toastr.success('Berhasil Mengaktifkan Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
            inactiveItem(id){
              axios.patch('/api/admin/web/admins/'+id, {type : 'inactive'})
              .then(res => {
                toastr.success('Berhasil menonaktifkan Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
        },
        filters: {
            moment(date) {
                return helper.formatDate(date);
            },
            ucword(value) {
                return helper.ucword(value);
            }
        }
    }
</script>
