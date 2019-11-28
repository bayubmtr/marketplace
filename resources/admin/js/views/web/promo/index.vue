<template>
	<div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center mt-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
                    <li class="breadcrumb-item active">Promo</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Daftar Promo</h4>
                        <div class="float-right mb-1">
                            <button v-b-modal.addLogistic v-on:click="type='create'">Tambah</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" v-if="items.total">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kode</th>
                                        <th>Deskripsi</th>
                                        <th>Aturan</th>
                                        <th>Status</th>
                                        <th>Terakhir diperbarui</th>
                                        <th style="width:150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in items.data" :key="item.id">
                                        <td v-text="item.title"></td>
                                        <td v-text="item.code"></td>
                                        <td v-text="item.content"></td>
                                        <td>
                                            <ul v-for="term in item.promo_term" :key="term.id">
                                                <li v-if="term">{{term.promo_variable.name}} {{term.condition}} {{term.value}}</li>
                                            </ul>
                                        </td> 
                                        <td class="text-center"><span class="badge-success" v-if="item.is_active == 1">Aktif</span><span v-else class="badge-warning">Nonaktif</span></td>
                                        <td v-text="item.updated_at"></td>
                                        <td>
                                            <click-confirm yes-class="btn btn-success" no-class="btn btn-danger">
                                                <button class="btn btn-danger btn-sm ml-3" @click.prevent="deleteItem(item.id)" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-secondary btn-sm" @click.prevent="getItem(item.id)" data-toggle="tooltip" title="Ubah"><i class="fa fa-pencil"></i></button>
                                                <button v-if="item.is_active == 1" class="btn btn-warning btn-sm"  @click.prevent="inactiveItem(item.id)" data-toggle="tooltip" title="Nonaktifkan"><i class="fa fa-times"></i></button>
                                                <button v-if="item.is_active == 0" class="btn btn-success btn-sm"  @click.prevent="activeItem(item.id)" data-toggle="tooltip" title="Aktifkan"><i class="fa fa-check"></i></button>
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
        <b-modal size="lg" id="addLogistic" title="Kelola Promo" no-close-on-backdrop hide-footer centered>
            <b-row>
               <b-col cols="3">
                    Kode Promo
              </b-col>
              <b-col cols="9">
                  <input type="text" class="form-control" v-model="itemForm.code">
              </b-col>
              <b-col cols="3" class="mt-3">
                    Judul
              </b-col>
              <b-col cols="9" class="mt-3">
                  <input type="text" class="form-control" v-model="itemForm.title">
              </b-col>
               <b-col cols="3" class="mt-3">
                    Deskripsi
              </b-col>
              <b-col cols="9" class="mt-3">
                  <textarea class="form-control" id="exampleFormControlTextarea1" v-model="itemForm.content" rows="5"></textarea>
              </b-col>
              <b-col cols="3" class="mt-3">
                    Gambar
              </b-col>
              <b-col cols="9" class="mt-3">
                  <input v-on:change="onLogoChange" id="logistic_logo" type="file"/>
              </b-col>
              <b-col cols="3" class="mt-3">
                  Status
              </b-col>
                <b-col cols="9" class="mt-3">
                    <select v-model="itemForm.status" class="form-control">
                        <option value="1">Actif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                    <b-button v-if="type == 'create'" v-on:click="createItem()" class="float-right mt-2" variant="outline-primary">Tambah</b-button>
                    <b-button v-if="type == 'update'" v-on:click="changeItem()" class="float-right mt-2" variant="outline-primary">Ubah</b-button>
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
                filterItemForm: {
                    sortBy : 'first_name',
                    order: 'desc',
                    status: '',
                    title: '',
                    pageLength: 5
                },
                itemForm : {
                  id : '',
                  name : '',
                  content : '',
                  status : '',
                  image : ''
                },
                type : ''
            }
        },
        mounted() {
            this.getItems();
        },
        methods: {
            getItems() {
                axios.get('/api/admin/web/promos')
                .then(res => {
                    this.items = res.data
                })
                .catch(err => {
                    console.error(err); 
                })
            },
            getItem(id) {
              this.type = 'update'
              this.itemForm.id = id;
                axios.get('/api/admin/web/promos/'+id)
                .then(res => {
                  this.itemForm = res.data
                  this.$bvModal.show('addLogistic');
                  this.itemForm.image = null
                })
                .catch(err => {
                  console.error(err); 
                })
            },
            getItemStatus(item){
                if(item == 1)
                    return '<span class="label label-success">Activated</span>';
                else if(item == 0)
                    return '<span class="label label-danger">Inactive</span>';
                else
                    return;
            },
            deleteItem(id){
              axios.delete('/api/admin/web/promos/'+id)
              .then(res => {
                toastr.success('Berhasil Menghapus Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
            createItem(){
              axios.post('/api/admin/web/promos', this.itemForm)
              .then(res => {
                toastr.success('Berhasil Menambah Logistik')
                this.getItems()
                this.$bvModal.hide('addLogistic');
              })
              .catch(err => {
                console.error(err); 
              })
            },
            changeItem(){
              axios.patch('/api/admin/web/promos/'+this.itemForm.id, this.itemForm)
              .then(res => {
                toastr.success('Berhasil Mengubah Logistik')
                this.getItems()
                this.$bvModal.hide('addLogistic');
              })
              .catch(err => {
                console.error(err); 
              })
            },
            activeItem(id){
              axios.patch('/api/admin/web/promos/'+id, {type : 'active'})
              .then(res => {
                toastr.success('Berhasil Mengaktifkan Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
            inactiveItem(id){
              axios.patch('/api/admin/web/promos/'+id, {type : 'inactive'})
              .then(res => {
                toastr.success('Berhasil menonaktifkan Logistik')
                this.getItems()
              })
              .catch(err => {
                console.error(err); 
              })
            },
            onLogoChange(e) {
              let files = e.target.files || e.dataTransfer.files;
              if (!files.length)
                  return;
              this.createLogoImage(files[0]);
            },
            createLogoImage(file) {
              let reader = new FileReader();
              let vm = this;
              reader.onload = (e) => {
                  vm.itemForm.image = e.target.result;
              };
              reader.readAsDataURL(file);
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
