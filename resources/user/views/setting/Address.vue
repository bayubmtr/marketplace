<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Daftar Alamat</span>
          <b-button variant="primary" v-b-modal.modal-CreateAddress class="float-right"><i class="fa fa-plus fa-lg"></i> Tambah Alamat</b-button>
        <div class="dropdown-divider mt-2"></div>
        <b-card v-for="(item, index) in Addresses" :key="index">
          <b-media right-align>
            <div slot="aside">
              <b-dropdown id="dropdown-1">
                <div slot="button-content"><i class="fa fa-ellipsis-v"></i></div>
                <b-dropdown-item v-b-modal.modal-EditAddress v-on:click="getAddress(item.id)">Edit Alamat</b-dropdown-item>
                <b-dropdown-item v-on:click="deleteAddress(item.id)">Hapus Alamat</b-dropdown-item>
                <b-dropdown-item v-on:click="setPrimary(item.id)">Jadikan Alamat Utama</b-dropdown-item>
                <b-dropdown-item v-on:click="setStoreAddress(item.id)">Jadikan Alamat Toko</b-dropdown-item>
                <b-dropdown-item v-on:click="setReturnAddress(item.id)">Jadikan Alamat Pengembalian</b-dropdown-item>
              </b-dropdown>
            </div>
          <b-row>
            <b-col cols="1" class="text-center">
              <p class="fa fa-map-marker fa-2x"></p>
            </b-col>
            <b-col cols="10">
              <b-row>
                <b-col cols="2">
                  <p class="text-sedang">Nama</p>
                  <p class="text-sedang">No Telepon</p>
                  <p class="text-sedang">Alamat</p>
                </b-col>
                <b-col cols="10">
                  <p class="text-sedang text-tebal">{{item.address_name}}
                    <span v-for="(item2, index2) in item.address_type" :key="index2">
                      <span class="badge badge-primary" v-show="item2.address_type_id == 1"> Alamat Utama </span> 
                      <span class="badge badge-success" v-show="item2.address_type_id == 2"> Alamat Toko </span> 
                      <span class="badge badge-danger" v-show="item2.address_type_id == 3"> Alamat Pengembalian </span>
                    </span>
                  </p>
                  <p class="text-sedang">{{item.phone_number}}</p>
                  <p class="text-sedang">{{item.address_detail}}</p>
                  <p class="text-sedang">{{item.village}}, {{item.district}}</p>
                  <p class="text-sedang">{{item.city}}, {{item.province}} {{item.zip_code}}</p>
                </b-col>
              </b-row>
            </b-col>
            <b-col cols="1">
            </b-col>
          </b-row>
          </b-media>
        </b-card>
      </b-card>
    </div>
    <!--edit address-->
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
                  <b-form-input list="my-list-id"  v-model="userAddressForm.zip_code"/>
                    <datalist id="my-list-id">
                      <option>Masukan jika tidak ada</option>
                      <option :value="selectedAddress.zip_code">{{ selectedAddress.zip_code }}</option>
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
                  id="checkbox4"
                  name="checkbox4"
                  v-model="userAddressForm.isPrimaryAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Utama
                </b-form-checkbox>
              </div>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox5"
                  name="checkbox5"
                  v-model="userAddressForm.isStoreAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Toko
                </b-form-checkbox>
              </div>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox6"
                  name="checkbox6"
                  v-model="userAddressForm.isReturnAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Pengembalian
                </b-form-checkbox>
              </div>
            </b-row>
        </b-modal>

        <!--new address-->
        <b-modal v-on:ok="saveAddress('create')" id="modal-CreateAddress" ok-title="Simpan" cancel-title="Batal" centered title="Tambah Alamat Baru"  size="lg" body-class="ChooseAddress">
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
                  <b-form-input list="my-list-id" v-model="userAddressForm.zip_code"/>
                    <datalist id="my-list-id">
                      <option>Masukan jika tidak ada</option>
                      <option :value="selectedAddress.zip_code">{{ selectedAddress.zip_code }}</option>
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
                  v-model="userAddressForm.isPrimaryAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Utama
                </b-form-checkbox>
              </div>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox2"
                  name="checkbox2"
                  v-model="userAddressForm.isStoreAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Toko
                </b-form-checkbox>
              </div>
              <div class="ml-3">
                <b-form-checkbox
                  id="checkbox3"
                  name="checkbox3"
                  v-model="userAddressForm.isReturnAddress"
                  value="true"
                  unchecked-value="false"
                >
                  Jadikan Alamat Pengembalian
                </b-form-checkbox>
              </div>
            </b-row>
        </b-modal>
  </div>
</template>



<script>
import { ModelListSelect } from 'vue-search-select'
import toastr from 'toastr'
export default {
  name: 'Address',
  components: {
    ModelListSelect,
  },
  data () {
    return {
      Addresses : {},
      AddAddress: false,
      addressesResult : [],
      userAddressForm : {
        id : '',
        address_name : '',
        recipient : '',
        phone_number : '',
        address_detail : '',
        zip_code : '',
        isPrimaryAddress : false,
        isStoreAddress : false,
        isReturnAddress : false
      },
      selectedAddress : {
        village_id : '',
        text : '',
        zip_code : ''
      },
    }
  },
  mounted() {
    this.getAddresses();
  },
  methods: {
    getAddresses(){
      axios.get('/api/user/addresses')
      .then(res => {
        this.Addresses = res.data
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    setPrimary(id){
      axios.patch('/api/user/addresses/'+id, {type : 'setPrimary'})
      .then(res => {
        toastr.success(res.data.message)
        this.getAddresses();
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    setStoreAddress(id){
      axios.patch('/api/user/addresses/'+id, {type : 'setStoreAddress'})
      .then(res => {
        toastr.success(res.data.message)
        this.getAddresses();
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    setReturnAddress(id){
      axios.patch('/api/user/addresses/'+id, {type : 'setReturnAddress'})
      .then(res => {
        toastr.success(res.data.message)
        this.getAddresses();
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    searchAddress(searchText) {
        if(searchText.length > 2 && searchText.length < 10){
          axios.get('/api/user/addresses?search='+searchText).then(response => {
          this.addressesResult = response.data
          })
        }
    },
    addAddress(){
      this.userAddressForm = {};
    },
    getAddress(id){
      axios.get('/api/user/addresses/'+id)
      .then(res => {
        this.userAddressForm = res.data
        this.selectedAddress.village_id = res.data.address_id
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    deleteAddress(id){
      axios.delete('/api/user/addresses/'+id)
      .then(res => {
        toastr.success(res.data.message)
        this.getAddresses();
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    saveAddress(formType){
      var data1 = this.userAddressForm;
      var data2 = this.selectedAddress;
      let merged = jQuery.extend(data1,data2);
      if(formType == 'create'){
        axios.post('/api/user/addresses', merged)
        .then(res => {
          this.getAddresses();
          toastr.success(res.data.message)
        })
        .catch(err => {
          toastr.error(res.response.data.message)
        })
      }else if(formType == 'update'){
        axios.patch('/api/user/addresses/'+this.userAddressForm.id, merged)
        .then(res => {
          this.getAddresses();
          toastr.success(res.data.message)
        })
        .catch(err => {
          toastr.error(res.response.data.message)
        })
      }
      
    },
  }
}
</script>