<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <br>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
                    <li class="breadcrumb-item active">Address</li>
                </ol>
            </div>
        </div>

        <div class="row">
          <div class="col-lg-3">
            <b-card>
              <div slot="header">
                <strong>Provinsi</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control listAddress" id="exampleFormControlSelect2" v-model="province">
                  <option v-for="province in provinces" :key="province.id" v-text="province.name" v-bind:value="province" v-on:click="provinceClick"></option>
                </select>
              </div>
            </b-card>
          </div>
          
          <div class="col-lg-3">
            <b-card>
              <div slot="header">
                <strong>Kota / Kabupaten</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control" id="exampleFormControlSelect3" v-model="city">
                  <option v-for="city in cities" :key="city.id" v-text="city.name" v-bind:value="city" v-on:click="cityClick"></option>
                </select>
              </div>
            </b-card>
          </div>
          <div class="col-lg-3">
            <b-card>
              <div slot="header">
                <strong>Kecamatan</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control" id="exampleFormControlSelect4" v-model="district">
                  <option v-for="district in districts" :key="district.id" v-text="district.name" v-bind:value="district" v-on:click="districtClick"></option>
                </select>
              </div>
            </b-card>
          </div>
          <div class="col-lg-3">
            <b-card>
              <div slot="header">
                <strong>Kelurahan</strong>
              </div>
              <div class="form-group">
                <select size="10" multiple class="form-control" id="exampleFormControlSelect4" v-model="village">
                  <option v-for="village in villages" :key="village.id" v-text="village.name" v-bind:value="village" v-on:click="villageClick"></option>
                </select>
              </div>
            </b-card>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-6" v-if="pShow != null">
              <form>
                <b-card>
                  <div class="form-group">
                    <label>Id Provinsi</label>
                    <input class="form-control" disabled name="name" value="" v-model="ProvinceForm.id" v-bind:placeholder="ProvinceForm.id">
                    <label>Nama Provinsi</label>
                    <input class="form-control" name="name" value="" v-model="ProvinceForm.name" v-bind:placeholder="ProvinceForm.name">
                  </div>
                  <b-button v-on:click="provinceUpdate" variant="primary">Update</b-button>
                  <b-button v-on:click="provinceCreate" variant="success">Create</b-button>
                  <b-button v-on:click="provinceDestroy" variant="danger">Delete</b-button>
                </b-card>
              </form>
            </div>
            <div class="col-lg-6" v-if="cShow != null">
              <form>
                <b-card>
                    <div class="form-group">
                      <label>Id Kota / Kab</label>
                      <input class="form-control" disabled name="name" value="" v-model="CityForm.id" v-bind:placeholder="CityForm.id">
                      <label>Id Provinsi</label>
                      <input class="form-control" name="province_id" value="" v-model="CityForm.province_id" v-bind:placeholder="CityForm.province_id">
                      <label>Nama Kota / Kab</label>
                      <input class="form-control" name="id" value="" v-model="CityForm.name" v-bind:placeholder="CityForm.name">
                      <label for="exampleFormControlSelect1">City type</label>
                      <select class="form-control" id="exampleFormControlSelect1" name="type" v-model="CityForm.type" v-bind:placeholder="CityForm.type">
                      <option value="Kota">Kota</option>
                      <option value="Kabupaten">Kabupaten</option>
                      </select>
                    </div>
                  <b-button v-on:click="cityUpdate" variant="primary">Update</b-button>
                  <b-button v-on:click="cityCreate" variant="success">Create</b-button>
                  <b-button v-on:click="cityDestroy" variant="danger">Delete</b-button>
                </b-card>
              </form>
            </div>
            <div class="col-lg-6" v-if="dShow != null">
              <form>
                <b-card>
                  <div class="form-group">
                      <label>Id Kecamatan</label>
                      <input class="form-control" disabled name="id" value="" v-model="DistrictForm.id" v-bind:placeholder="DistrictForm.id">
                      <label>Id Kota / Kab</label>
                      <input class="form-control" name="city_id" value="" v-model="DistrictForm.city_id" v-bind:placeholder="DistrictForm.city_id">
                      <label>Nama Kelurahan</label>
                      <input class="form-control" name="name" value="" v-model="DistrictForm.name" v-bind:placeholder="DistrictForm.name">
                  </div>
                  <b-button v-on:click="districtUpdate" variant="primary">Update</b-button>
                  <b-button v-on:click="districtCreate" variant="success">Create</b-button>
                  <b-button v-on:click="districtDestroy" variant="danger">Delete</b-button>
                </b-card>
              </form>
            </div>
            <div class="col-lg-6" v-if="vShow != null">
              <form @submit.prevent="villageUpdate">
                <b-card>
                  <div class="form-group">
                      <label>Id Kelurahan</label>
                      <input class="form-control" disabled name="id" value="" v-model=" VillageForm.id" v-bind:placeholder="VillageForm.id">
                      <label>Id Kecamatan</label>
                      <input class="form-control" name="district_id" value="" v-model=" VillageForm.district_id" v-bind:placeholder="VillageForm.district_id">
                      <label>Nama Kelurahan</label>
                      <input class="form-control" name="name" value="" v-model=" VillageForm.name" v-bind:placeholder="VillageForm.name">
                      <label>Kode Pos</label>
                      <input class="form-control" name="zip_code" value="" v-model=" VillageForm.zip_code" v-bind:placeholder="DistrictForm.zip_code">
                  </div>
                  <b-button v-on:click="villageUpdate" variant="primary">Update</b-button>
                  <b-button v-on:click="villageCreate" variant="success">Create</b-button>
                  <b-button v-on:click="villageDestroy" variant="danger">Delete</b-button>
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
                items : {},
                provinces : {},
                cities : {},
                districts : {},
                villages : {},
                province : '',
                city : '',
                district : '',
                village : '',
                pShow : null,
                cShow : null,
                dShow : null,
                vShow : null,
                xxx : 'wkwk',
                ProvinceForm : new Form({
                  name : '',
                  id : '',
                  req_type : 'province'
                }),
                CityForm : new Form({
                  name : '',
                  id : '',
                  province_id : '',
                  type : '',
                  req_type : 'city'
                }),
                DistrictForm : new Form({
                  name : '',
                  id : '',
                  city_id : '',
                  req_type : 'district'
                }),
                VillageForm : new Form({
                  name : '',
                  id : '',
                  district_id : '',
                  zip_code : '',
                  req_type : 'village'
                })
            }
        },
        mounted() {
            this.getProvinces();
        },
        watch: {
            // whenever question changes, this function will run
            province: function (province) {
                this.cities = null;
                this.districts = null;
                this.villages = null;
                this.ProvinceForm.id = this.province['0']['id'];
                this.ProvinceForm.name = this.province['0']['name'];
                this.getCities();
            
            },
            city: function (city) {
                this.villages = null;
                this.CityForm.id = this.city['0']['id'];
                this.CityForm.province_id = this.city['0']['province_id'];
                this.CityForm.name = this.city['0']['name'];
                this.CityForm.type = this.city['0']['type'];
                this.getDistricts();
                
            },
            district: function (district) {
                this.DistrictForm.id = this.district['0']['id'];
                this.DistrictForm.city_id = this.district['0']['city_id'];
                this.DistrictForm.name = this.district['0']['name'];
                this.getVillages();
            },
            village: function (village) {
                this.VillageForm.id = this.village['0']['id'];
                this.VillageForm.district_id = this.village['0']['district_id'];
                this.VillageForm.name = this.village['0']['name'];
                this.VillageForm.zip_code = this.village['0']['zip_code'];
            }
        },
        methods: {
           
            getProvinces() {
                axios.get('/api/admin/web/administrative?type=province')
                    .then(response => this.provinces = response.data );
            },
            getCities(){
                axios.get('/api/admin/web/administrative?type=city&province_id='+this.ProvinceForm.id)
                    .then(response => this.cities = response.data );
            },
            getDistricts(){
                axios.get('/api/admin/web/administrative?type=district&city_id='+this.CityForm.id)
                    .then(response => this.districts = response.data );
            },
            getVillages(){
                axios.get('/api/admin/web/administrative?type=village&district_id='+this.DistrictForm.id)
                    .then(response => this.villages = response.data );
            },
            provinceCreate() {
                if (this.ProvinceForm.name && this.ProvinceForm.id) {
                    this.ProvinceForm.post('/api/admin/web/administrative')
                    .then(response => {
                        toastr['success'](response.message);
                        this.getProvinces();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            provinceUpdate() {
                if (this.ProvinceForm.name && this.ProvinceForm.id) {
                    this.ProvinceForm.patch('/api/admin/web/administrative/'+this.ProvinceForm.id, this.ProvinceForm)
                    .then(response => {
                        toastr['success'](response.message);
                        this.getProvinces();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            provinceDestroy() {
                axios.delete('/api/admin/web/administrative/'+this.ProvinceForm.id+'?req_type=province')
                .then(response => {
                    toastr['success'](response.data.message);
                    this.getProvinces();
                });
            },
            cityCreate() {
                if (this.CityForm.name && this.CityForm.id && this.CityForm.province_id && this.CityForm.type) {
                    this.CityForm.post('/api/admin/web/administrative')
                    .then(response => {
                        toastr['success'](response.message)
                        if (response.message){
                          this.getCities();
                        }
                        
                    }).catch(response => {
                      $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            cityUpdate() {
                if (this.CityForm.name && this.CityForm.id && this.CityForm.province_id && this.CityForm.type) {
                    this.CityForm.patch('/api/admin/web/administrative/'+this.CityForm.id, this.CityForm.id)
                    .then(response => {
                        toastr['success'](response.message);
                        this.getCities();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            cityDestroy() {
                axios.delete('/api/admin/web/administrative/'+this.CityForm.id+'?req_type=city')
                .then(response => {
                  toastr['success'](response.data.message);
                  this.xxx = response.message
                  });
                this.getCities();
            },
            districtCreate() {
                if (this.DistrictForm.name && this.DistrictForm.id && this.DistrictForm.city_id) {
                    this.DistrictForm.post('/api/admin/web/administrative')
                    .then(response => {
                        toastr['success'](response.message);
                        this.getDistricts();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            districtUpdate() {
                if (this.DistrictForm.name && this.DistrictForm.id && this.DistrictForm.city_id) {
                    this.DistrictForm.patch('/api/admin/web/administrative/'+this.DistrictForm.id, this.DistrictForm)
                    .then(response => {
                        toastr['success'](response.message);
                        this.getDistricts();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            districtDestroy() {
                axios.delete('/api/admin/web/administrative/'+this.DistrictForm.id+'?req_type=district')
                .then(response => toastr['success'](response.data.message));
                this.getDistricts();
            },
            villageCreate() {
                if (this.VillageForm.name && this.VillageForm.id && this.VillageForm.district_id && this.VillageForm.zip_code) {
                    this.VillageForm.post('/api/admin/web/administrative')
                    .then(response => {
                        toastr['success'](response.message);
                        this.getVillages();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            villageUpdate() {
                if (this.VillageForm.name && this.VillageForm.id && this.VillageForm.district_id && this.VillageForm.zip_code) {
                    this.VillageForm.patch('/api/admin/web/administrative/'+this.VillageForm.id, this.VillageForm)
                    .then(response => {
                        toastr['success'](response.message);
                        this.getVillages();
                    }).catch(response => {
                        $.each(response.errors, function(field, val) {
                          toastr['error'](val);
                      });
                    })
                }else{
                    toastr['error']('check the form !');
                }
            },
            villageDestroy() {
                axios.delete('/api/admin/web/administrative/'+this.VillageForm.id+'?req_type=village')
                .then(response => toastr['success'](response.data.message));
                this.getVillages();
            },
            provinceClick() {
                this.cShow = null;
                this.dShow = null;
                this.vShow = null;
                this.pShow = true;
            },
            cityClick() {
                this.pShow = null;
                this.dShow = null;
                this.vShow = null;
                this.cShow = true;
            },
            districtClick() {
                this.pShow = null;
                this.cShow = null;
                this.vShow = null;
                this.dShow = true;
            },
            villageClick() {
                this.pShow = null;
                this.cShow = null;
                this.dShow = null;
                this.vShow = true;
            }
        },
        filters: {
        }
    }
</script>
