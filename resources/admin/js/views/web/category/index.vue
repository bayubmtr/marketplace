<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <br>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <b-card>
              <div slot="header">
                <strong>Category</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control listAddress" id="exampleFormControlSelect2" v-model="category">
                  <option v-for="item in categories" :key="item.id" v-text="item.name" v-bind:value="item" v-on:click="categoryClick"></option>
                </select>
              </div>
            </b-card>
          </div>
          
          <div class="col-lg-4">
            <b-card>
              <div slot="header">
                <strong>Sub Category 1</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control" id="exampleFormControlSelect3" v-model="sub_category">
                  <option v-for="(data, index) in sub_categories" :key="index" :value="data">{{data.name}}</option>
                </select>
              </div>
            </b-card>
          </div>
          <div class="col-lg-4">
            <b-card>
              <div slot="header">
                <strong>Sub Category 2</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control" id="exampleFormControlSelect4" v-model="sub_sub_category">
                  <option v-for="sub_sub_category in sub_sub_categories" :key="sub_sub_category.id" v-text="sub_sub_category.name" v-bind:value="sub_sub_category" v-on:click="subSubCategoryClick"></option>
                </select>
              </div>
            </b-card>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-6" v-if="aShow != null">
              <form>
                <b-card>
                  <div class="form-group">
                    <label>Id Kategori</label>
                    <input class="form-control" disabled name="name" value="" v-model="CategoryForm.id" v-bind:placeholder="CategoryForm.id">
                    <label>Nama Kategori</label>
                    <input class="form-control" name="name" value="" v-model="CategoryForm.name" v-bind:placeholder="CategoryForm.name">
                    <label>Id Kategori Induk</label>
                    <input class="form-control" name="name" value="" v-model="CategoryForm.parent_id" v-bind:placeholder="CategoryForm.parent_id">
                  </div>
                  <b-button v-on:click="updateCategory()" variant="primary">Update</b-button>
                  <b-button v-on:click="createCategory()" variant="success">Create</b-button>
                </b-card>
              </form>
            </div>
        </div>
        
    </div>
</template>

<script>
    import toastr from 'toastr'
    import pagination from 'laravel-vue-pagination'
    import helper from '../../../services/helper'
    import ClickConfirm from 'click-confirm'


    export default {
        components : { pagination, ClickConfirm, toastr },
        data() {
            return {
                categories : {},
                sub_categories : {},
                sub_sub_categories : {},
                category : {},
                sub_category : {},
                sub_sub_category : {},
                aShow : null,
                bShow : null,
                cShow : null,
                CategoryForm : new Form({
                  id : '',
                  name : '',
                  parent_id : null
                }),
            }
        },
        mounted() {
            this.getCategories();
        },
        watch: {
            // whenever question changes, this function will run
            category: function () {
                this.sub_categories = null;
                this.sub_sub_categories = null;
                this.getSubCategories(this.category[0].id);
                this.CategoryForm = this.category[0]
            
            },
            sub_category: function () {
                this.getSubSubCategories(this.sub_category[0].id);
                this.CategoryForm = this.sub_category[0]
            },
            sub_sub_category: function () {
                this.CategoryForm = this.sub_sub_category[0]
            }
        },
        methods: {
           
            getCategories() {
                axios.get('/api/admin/web/categories?type=category')
                    .then(response => this.categories = response.data );
            },
            getSubCategories(id) {
                if(typeof id == 'number'){
                    axios.get('/api/admin/web/categories?type=sub_category&parent_id='+id)
                        .then(response => this.sub_categories = response.data );
                }
            },
            getSubSubCategories(id) {
                if(typeof id == 'number'){
                    axios.get('/api/admin/web/categories?type=sub_sub_category&parent_id='+id)
                        .then(response => this.sub_sub_categories = response.data );
                 }
            },
            createCategory(){
                axios.post('/api/admin/web/categories',this.CategoryForm)
                .then(res => {
                    toastr.success('Berhasil Menambah Kategori')
                    this.getCategories();
                })
                .catch(err => {
                    console.error(err); 
                })
            },
            updateCategory(){
                axios.patch('/api/admin/web/categories/'+this.CategoryForm.id,this.CategoryForm)
                .then(res => {
                    toastr.success('Berhasil Mengubah Kategori')
                    this.getCategories();
                })
                .catch(err => {
                    console.error(err); 
                })
            },
            categoryClick() {
                this.aShow = true;
            },
            subCategoryClick() {
                this.aShow = true;
            },
            subSubCategoryClick() {
                this.aShow = true;
            },
        },
        filters: {
        }
    }
</script>
