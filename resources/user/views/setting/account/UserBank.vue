<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Daftar Rekening Bank</span>
          <b-button variant="primary" v-b-modal.modal-CreateAddress class="float-right"><i class="fa fa-plus fa-lg"></i> Tambah Rekening</b-button>
        <div class="dropdown-divider mt-2"></div>
        <b-card v-for="(item, index) in banks" :key="index">
          <b-media right-align>
            <div slot="aside">
              <span class="fa fa-trash mouse-pointer" v-on:click="deleteBank(item.id)"></span>
            </div>
          <b-row>
            <b-col cols="10">
              <b-row>
                <b-col cols="4">
                  <p class="text-sedang">Nama Bank</p>
                  <p class="text-sedang">Nama Pemilik</p>
                  <p class="text-sedang">Nomor Rekening</p>
                </b-col>
                <b-col cols="8">
                  <p class="text-sedang">{{item.bank_name}}</p>
                  <p class="text-sedang">{{item.account_name}}</p>
                  <p class="text-sedang">{{item.account_number}}</p>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          </b-media>
        </b-card>
      </b-card>
    </div>
        <!--new bank-->
        <b-modal v-on:ok="newBank()" id="modal-CreateAddress" ok-title="Simpan" cancel-title="Batal" centered title="Tambah Rekening Bank Baru"  size="m" body-class="ChooseAddress">
            <b-row>
              <b-col cols="12">
                <b-form-group label-for="input1" label="Nama Bank">
                  <b-form-input v-model="userBankForm.bank_name"/>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label-for="input2" label="Nama Pemilik Rekening">
                  <b-form-input v-model="userBankForm.account_name"/>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label-for="input3" label="Nomor Rekening">
                  <b-form-input v-model="userBankForm.account_number"/>
                </b-form-group>
              </b-col>
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
      banks : {},
      userBankForm : {
        bank_name : '',
        account_name : '',
        account_number : ''
      },
    }
  },
  mounted() {
    this.getbanks();
  },
  methods: {
    getbanks(){
      axios.get('/api/user/banks')
      .then(res => {
        this.banks = res.data
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    newBank(){
      axios.post('/api/user/banks',this.userBankForm)
      .then(res => {
        toastr.success('Berhasil Menambah rekening')
        this.getbanks();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    deleteBank(id){
      axios.delete('/api/user/banks/'+id)
      .then(res => {
        toastr.success('Berhasil Menghapus Rekening');
        this.getbanks();
      })
      .catch(err => {
        console.error(err); 
      })
    }
  }
}
</script>