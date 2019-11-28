<template>
  <div class="animated fadeIn">
        <div>
        <b-card-header class="border mb-3 pb-3 pt-2">
          <strong class="text-muted">Pilih Alamat Pengiriman</strong><button v-on:click="resetSelectedAddress()" v-b-modal.modal-AddAddress type="button" class="btn btn-outline-primary btn-sm float-right"><i class="fa fa-plus"></i> Alamat Baru</button><button v-b-modal.modal-ChooseAddress type="button" class="btn btn-outline-primary btn-sm float-right mr-2" v-if="addresses"><i class="fa fa-address-card"></i> Pilih Alamat</button>
        </b-card-header>
        <template v-if="selectedCart[getFirstCartKey()]">
          <b-card v-if="selectedCart[firstCartKey][0].address">
            {{setEnableLogistic()}}
            <div>
              <span class="text-tebal">{{selectedCart[firstCartKey][0].address.recipient}}</span>
            </div>
            <div>
              <span>{{selectedCart[firstCartKey][0].address.phone_number}}</span>
            </div>
            <div>
              <span>{{selectedCart[firstCartKey][0].address.address_detail}}</span>
            </div>
            <div v-if="selectedCart[firstCartKey][0].address.address_village">
              <div>
                <span>Kelurahan {{selectedCart[firstCartKey][0].address.address_village.name}}, </span>
                <span>Kecamatan {{selectedCart[firstCartKey][0].address.address_village.address_district.name}}, </span>
              </div>
              <div>
                <span>Kabupaten/Kota {{selectedCart[firstCartKey][0].address.address_village.address_district.address_city.name}}, </span>
                <span>Provinsi {{selectedCart[firstCartKey][0].address.address_village.zip_code}} </span>
              </div>
            </div>
            <div>
                <button v-b-modal.modal-EditAddress v-on:click="editAddress(selectedCart[firstCartKey][0].address_id)" type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-pencil"></i> Ubah Alamat</button>
            </div>
          </b-card>
          <b-card v-else>
            <div class="alert alert-warning alert-dismissible" v-if="!addresses">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Belum Memiliki Alamat !</strong> Silahkan Tambah Alamat Baru.
            </div>
            <div class="alert alert-warning alert-dismissible" v-else>
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Perhatian !</strong> Silahkan Pilih Alamat Pengiriman.
            </div>
          </b-card>
        </template>
        <div v-for="data in selectedCart" :key="data.id">
            <b-card v-if="data[0].product_id">
            <div slot="header" class="pl-2">
                <span>Penjual :</span><span class="text-tebal"> <router-link class="text-dark" :to="'/'+data[0].product.store.store_url"> {{data[0].product.store.name}}</router-link></span>
            </div>
                <div v-for="(item, index) in data" :key="item.id">
                <b-row>
                    <b-col cols="1" class="m-0 ml-2 text-left">
                    <div class="align-middle" style="display: inline-block;">
                        <img class="foto-product-thumb" :src="item.product.photo.small" alt="" style="max-height:59px!important">
                    </div>
                    </b-col>
                    <b-col cols="6">
                    <span class="text-tebal text-sedang">
                        <router-link :to="'/'+item.product.store.store_url+'/'+item.product_id+'/'+Slug(item.product.name)">{{item.product.name}}</router-link>
                    </span>
                    <div>
                        <span class="text-sedang text-tebal detail-product-price">
                        {{item.product.price*item.quantity | formatRupiah}}
                        </span>
                    </div>
                    <span class="text-muted">Berat : </span><span>{{item.product.weight}} Kg </span><span class="text-muted">Jumlah : </span><span>{{item.quantity}} </span>
                    <b-row>
                    <b-col cols="6">
                        <div>
                        <small>Catatan Untuk Penjual : </small>
                        <input :value="item.note" class="form-control" type="text" style="width: 300px" @blur="setNote(item.id)">
                        </div>
                    </b-col>
                    </b-row>
                    </b-col>
                    <b-col class="m-0 p-0" cols="4" v-if="index == 0">
                    <b-row>
                        <b-col cols="6" class="text-right">
                        <div class="pt-1">
                            <span>
                            Kurir : 
                            </span>
                        </div>
                        <div class="pt-4">
                            <span>
                            Layanan : 
                            </span>
                        </div>
                        <div class="pt-4">
                            <span>
                            Biaya kirim : 
                            </span>
                        </div>
                        </b-col>
                        <b-col cols="6" class="p-0">
                        <select :disabled="enableLogistic" v-on:change="setSelectedLogistic(item.product.store_id, item.id)" name="" class="form-control" id="">
                            <option value="">Pilih Kurir</option>
                            <option v-for="(item, index) in item.product.store.store_logistic" :key="index" :value="item.logistic.code">{{item.logistic.name}}</option>
                        </select>
                        <div v-if="logisticService">
                            <select v-model="resetLogisticService[item.product.store_id]" v-on:change="setSelectedLogisticService(item.product.store_id, item.id)" name="" class="form-control mt-2" id="">
                            <template v-if="logisticService[item.product.store_id]">
                                <option 
                                v-for="(item, index) in logisticService[item.product.store_id].costs" 
                                :key="index"  
                                :value="index">
                                {{item.service}}
                                </option>
                            </template>
                            </select>
                        </div>
                        <div class="pt-3" v-if="shippingCost">
                            <span class=" detail-product-price">{{shippingCost[item.product.store_id] | formatRupiah}}</span>
                        </div>
                        </b-col>
                    </b-row>
                    </b-col>
                </b-row>
            </div>
            </b-card>
        </div>
        <!--Modal-->
        <b-modal id="modal-ChooseAddress" centered title="Pilih Alamat" body-class="ChooseAddress scroll" hide-footer>
          <div class="border mb-2 p-1 rounded"  v-for="(data) in addresses" :key="data.id">
            <b-media right-align vertical-align="center">
              <button v-on:click="setAddress(data.id)" @click="$bvModal.hide('modal-ChooseAddress')" slot="aside" type="button" class="btn btn-outline-primary" vertical-align="center">Pilih</button>
                  <div>
                    <span class="text-tebal">{{data.recipient}} </span><span class="text-muted">({{data.address_name}}) </span><span v-if="data.isPrimary == true" class="badge badge-primary">Alamat Utama</span>
                  </div>
                  <div>
                    <span>{{data.phone_number}}</span>
                  </div>
                  <div>
                    <span>{{data.address_detail}}</span>
                  </div>
                  <div>
                    <span>Kel. {{data.village}} </span>
                    <span>Kec. {{data.district}} </span>
                  </div>
                  <div>
                    <span>{{data.city}} 
                      </span><span>{{data.province}} 
                        </span><span>{{data.zip_code}} </span>
                  </div>
                  <div>
                    <span v-on:click="editAddress(data.id)" v-b-modal.modal-EditAddress class="text-tebal text-primary mouse-pointer">Edit Alamat</span>
                  </div>
            </b-media>
          </div>
        </b-modal>
        <b-modal v-on:ok="saveAddress('update')" id="modal-EditAddress" ok-title="Simpan" cancel-title="Batal" centered title="Edit Alamat"  size="lg" body-class="ChooseAddress">
            <b-row>
              <b-col cols="12">
                <b-form-group label-for="input1" label="Nama Alamat">
                  <b-form-input v-model="userAddressForm.address_name" id="input1"/>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group label-for="input2" label="Nama Penerima">
                  <b-form-input v-model="userAddressForm.recipient" id="input2"/>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group label-for="input3" label="Nomor HP">
                  <b-form-input v-model="userAddressForm.phone_number" id="input3"/>
                </b-form-group>
              </b-col>
              <b-col cols="9">
                <b-form-group label-for="input4" label="Kecamatan atau Kelurahan">
                  <model-list-select :list="addressesResult"
                             option-value="village_id"
                             option-text="text"
                             v-model="selectedAddress"
                             placeholder="select item"
                             @searchchange="searchAddress">
                             
                  </model-list-select>
                </b-form-group>
              </b-col>
              <b-col cols="3">
                <b-form-group label-for="input5" label="Kode POS">
                  <b-form-input list="my-list-id" />
                    <datalist id="my-list-id">
                      <option>Masukan jika tidak ada</option>
                      <option>{{ selectedAddress.zip_code }}</option>
                    </datalist>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label-for="input6" label="Alamat">
                  <b-form-textarea v-model="userAddressForm.address_detail" rows="3" id="input6"/>
                </b-form-group>
              </b-col>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox1"
                  name="checkbox1"
                  v-model="userAddressForm.isPrimary"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Utama
                </b-form-checkbox>
              </div>
            </b-row>
        </b-modal>

        <!--new address-->
        <b-modal v-on:ok="saveAddress('create')" id="modal-AddAddress" ok-title="Simpan" cancel-title="Batal" centered title="Tambah Alamat Baru"  size="lg" body-class="ChooseAddress">
            <b-row>
              <b-col cols="12">
                <b-form-group label-for="input1" label="Nama Alamat">
                  <b-form-input v-model="userAddressForm.address_name" id="input1"/>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group label-for="input2" label="Nama Penerima">
                  <b-form-input v-model="userAddressForm.recipient" id="input2"/>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group label-for="input3" label="Nomor HP">
                  <b-form-input v-model="userAddressForm.phone_number" id="input3"/>
                </b-form-group>
              </b-col>
              <b-col cols="9">
                <b-form-group label-for="input4" label="Kecamatan atau Kelurahan">
                  <model-list-select :list="addressesResult"
                             option-value="village_id"
                             option-text="text"
                             v-model="selectedAddress"
                             placeholder="select item"
                             @searchchange="searchAddress">
                             
                  </model-list-select>
                </b-form-group>
              </b-col>
              <b-col cols="3">
                <b-form-group label-for="input5" label="Kode POS">
                  <b-form-input list="my-list-id" />
                    <datalist id="my-list-id">
                      <option>Masukan jika tidak ada</option>
                      <option>{{ selectedAddress.zip_code }}</option>
                    </datalist>
                </b-form-group>
                <span>{{searchAddress.village_id}}</span>
              </b-col>
              <b-col cols="12">
                <b-form-group label-for="input6" label="Alamat">
                  <b-form-textarea v-model="userAddressForm.address_detail" rows="3" id="input6"/>
                </b-form-group>
              </b-col>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox1"
                  name="checkbox1"
                  v-model="userAddressForm.isPrimary"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Utama
                </b-form-checkbox>
              </div>
            </b-row>
        </b-modal>
        
        <!--End Modal-->
   </div>
  </div>
</template>

<script>
import { ModelListSelect } from 'vue-search-select'
import helper from '../../services/helper'
export default {
  props: [
    'selectedCart',
    'logistic',
    'totalShippingCost'
  ],
  name: 'shipmment',
  components: {
    ModelListSelect,
  },
  data: function () {
    return {
      addresses : {},
        addressesResult : [],
        addressForm : {
            req_type : "set_address",
            oneAddress : true,
            address_id : ''
        },
        userAddressForm : {
            id : '',
            address_name : '',
            recipient : '',
            phone_number : '',
            address_detail : '',
            isPrimary : false,
        },
        selectedAddress : {
            village_id : '',
            text : '',
            zip_code : ''
        },
        logistic_form : '',
        firstCartKey : '',
        noteForm: {
            note: ''
        },
        searchText: {
            search: ''
        },
        selectedIndexLogistic : [],
        selectedLogisticService : [],
        shippingCost : [],
        logisticService : [],
        resetLogisticService : [],
        shippingDetails : {
            req_type : '',
            logistic : '',
            store_id : '',
            id : '',
            OneAddress : true,
            logistic_service : '',
            shipping_cost : 0
        },
        enableLogistic : true
        }
  },
  mounted() {
    this.getAllUserAddress();
    this.$emit('selectedCart', []);
    this.$emit('selectedCart', this.selectedCart);
    this.getSelectedCart();
  },
  computed: {
  },
  watch: {
  },
  methods: {
    getAllUserAddress(){
      axios.get('/api/user/addresses')
      .then(res => {
        this.addresses = res.data
      })
      .catch(err => {
        this.addresses = null;
      })
    },
    setEnableLogistic(){
        this.enableLogistic = false
    },
    getFirstCartKey(){
      for(var x in this.selectedCart){
        this.firstCartKey = x;
        return x;
      }
    },
    getSelectedCart(){
        this.$emit('getSelectedCart');
    },
    getAllUserAddress(){
      axios.get('/api/user/addresses')
      .then(res => {
        this.addresses = res.data
      })
      .catch(err => {
        this.addresses = null;
      })
    },
    setNote(cart_id){
        this.noteForm.note = event.target.value
        axios.patch('/api/user/carts/'+cart_id,this.noteForm)
        .then(res => {
        })
        .catch(err => {
            console.error(err); 
        })
    },
    setAddress(address_id) {
        this.addressForm.address_id = address_id;
        axios.patch('/api/user/carts/'+address_id, this.addressForm)
        .then(res => {
            this.$emit('getSelectedCart');
        })
        .catch(err => {
            console.error(err); 
        })
        },
    editAddress(address_id){
      this.getUserAddress(address_id);
    },
    resetSelectedAddress(){
      this.userAddressForm.id = null;
      this.userAddressForm.address_name = null;
      this.userAddressForm.recipient = null;
      this.userAddressForm.phone_number = null;
      this.userAddressForm.address_detail = null;
      this.userAddressForm.isPrimary = false;
    },
    searchAddress(searchText) {
      this.searchText.search = searchText
        if(this.searchText.search.length > 2 && this.searchText.search.length < 15){
          axios.get('/api/user/addresses?search='+this.searchText.search).then(response => {
          this.addressesResult = response.data
          })
        }
    },
    getUserAddress(id){
      axios.get('/api/user/addresses/'+id)
      .then(res => {
        this.userAddressForm = res.data
        this.selectedAddress.village_id = res.data.address_id
      })
      .catch(err => {
        console.error(err); 
      })
    },
    addAddress(){
      this.userAddressForm = {};
    },
    saveAddress(formType){
      var data1 = this.userAddressForm;
      var data2 = this.selectedAddress;
      let merged = jQuery.extend(data1,data2);
      if(formType == 'create'){
        axios.post('/api/user/addresses', merged)
        .then(res => {
          this.getAllUserAddress();
        })
        .catch(err => {
          console.error(err); 
        })
      }else if(formType == 'update'){
        axios.patch('/api/user/addresses/'+this.userAddressForm.id, merged)
        .then(res => {
          this.getAllUserAddress();
        })
        .catch(err => {
          console.error(err); 
        })
      }
      
    },
    setSelectedLogistic(store_id, cart_id){
      if(event.target.value != ''){
        Vue.set( this.shippingCost, store_id, null)
        this.shippingCost.splice(store_id, 1)
        this.shippingDetails.store_id = store_id
        this.shippingDetails.id = cart_id
        this.shippingDetails.req_type = 'set_logistic'
        Vue.set( this.selectedIndexLogistic, store_id, event.target.value)
        this.shippingDetails.logistic = event.target.value;
        console.log(cart_id)
        axios.patch('/api/user/carts/'+cart_id, this.shippingDetails)
        .then(res => {
            Vue.set( this.logisticService, store_id, res.data)
        })
        .catch(err => {
            console.error(err); 
        })
      }else{
        Vue.set( this.logisticService, store_id, null)
      }
    },
    setSelectedLogisticService(store_id, cart_id){
        var $cost = this.logisticService[store_id].costs[event.target.value].cost[0].value
        Vue.set( this.shippingCost, store_id, $cost)
        this.shippingDetails.req_type = 'set_logistic_service'
        this.shippingDetails.shipping_cost = $cost;
        this.shippingDetails.logistic_service = this.logisticService[store_id].costs[event.target.value].service
        axios.patch('/api/user/carts/'+cart_id, this.shippingDetails)
            .then(res => {
        }).catch(err => {
            console.error(err); 
        })
    },
    Slug(value){
      return helper.toSlug(value);
    }
  },
  watch: {
    shippingCost: function(){
        var $totalShippingCost = 0;
        var $count = 0;
        for(var i in this.shippingCost){
            var $totalShippingCost = this.shippingCost[i] + $totalShippingCost;
            $count++
        }
        console.log($count)
        this.$emit('updateTotalShippingCost', $totalShippingCost);
        this.$emit('updateTotalShippingCount', $count);
    },
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  }
}
</script>

