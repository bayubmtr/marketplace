<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Dompet Saya</span><br><br>
        <b-card>
          <b-row>
            <b-col cols="2">
              Saldo
            </b-col>
            <b-col cols="3">
              <strong>{{balance | formatRupiah}}</strong>
            </b-col>
            <b-col cols="7">
              <button v-on:click="getbanks()" v-b-modal.modal-CreateAddress type="button" class="btn btn-outline-primary">Tarik ke Rekeing Bank</button>
              <button v-on:click="getWithdraws()" type="button" class="btn btn-outline-primary">Riwayat Penarikan</button>
              <button v-on:click="getIncomes()" type="button" class="btn btn-outline-primary">Riwayat Pendapatan</button>
            </b-col>
          </b-row>
        </b-card>
      </b-card>
      <b-card v-if="show_withdraws">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">Bank</th>
              <th scope="col">Nama Rekening</th>
              <th scope="col">Nomor Rekening</th>
              <th scope="col">Jumlah</th>
              <th scope="col">status</th>
              <th scope="col">tanggal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in withdraws" :key="index">
              <td>{{item.user_bank.bank_name}}</td>
              <td>{{item.user_bank.account_name}}</td>
              <td>{{item.user_bank.account_number}}</td>
              <td>{{item.amount | formatRupiah}}</td>
              <td>{{item.withdraw_status.name}}</td>
              <td>{{item.created_at | moment('DD MMM YYYY - hh:mm')}}</td>
            </tr>
          </tbody>
        </table>
      </b-card>
      <b-card v-if="show_incomes">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">Deskripsi</th>
              <th scope="col">Jumlah</th>
              <th scope="col">tanggal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in incomes" :key="index">
              <td>{{item.description}}</td>
              <td>{{item.amount | formatRupiah}}</td>
              <td>{{item.created_at | moment('DD MMM YYYY - hh:mm')}}</td>
            </tr>
          </tbody>
        </table>
      </b-card>
    </div>
    <!--new withdraw-->
    <b-modal v-on:ok="makeWithdraw()" id="modal-CreateAddress" ok-title="Tarik Dompet" cancel-title="Batal" centered title="Tambah Rekening Bank Baru"  size="m" body-class="ChooseAddress">
        <b-col cols="12">
          <b-form-group label-for="input1" label="Ke Bank">
            <select class="form-control" v-model="form.bank_id">
              <option v-for="(item, index) in banks" :key="index" :value="item.id">{{item.bank_name}} ({{item.account_number}})</option>
            </select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label-for="input2" label="Jumlah">
            <b-form-input :class="validation" v-model="form.amount"/>
            <div v-if="validation == 'is-invalid'" class="invalid-feedback">Jumlah tidak lebih dari saldo dan lebih dari Rp 50.000</div>
          </b-form-group>
        </b-col>
    </b-modal>
  </div>
</template>

<script>
import helper from '../../../services/helper'
export default {
  name: 'Promo',
  components: {
  },
  data () {
    return {
      banks: {},
      show_withdraws : false,
      show_incomes : false,
      withdraws: {},
      incomes: {},
      balance : '',
      validation : 'is-invalid',
      form : {
        bank_id : '',
        amount : '',
      }
    }
  },
  mounted() {
    this.getWallet();
  },
  methods: {
    getWallet(){
      axios.get('/api/user/wallets')
      .then(res => {
        this.balance = res.data.balance;
      })
      .catch(err => {
        
      })
    },
    getbanks(){
      axios.get('/api/user/banks')
      .then(res => {
        this.banks = res.data
      })
      .catch(err => {
        toastr.error(res.response.data.message)
      })
    },
    makeWithdraw(){
      axios.post('/api/user/withdraws',this.form)
      .then(res => {
        this.getWallet();
        toastr.success('Berhasi ! Silahkan Tunggu Hingga Transaksi Disetujui')
      })
      .catch(err => {
        console.error(err); 
      })
    },
    getWithdraws(){
      axios.get('/api/user/withdraws')
      .then(res => {
        this.withdraws = res.data
        this.show_incomes = false;
        this.show_withdraws = true;
      })
      .catch(err => {
        console.error(err); 
      })
    },
    getIncomes(){
      axios.get('/api/user/incomes')
      .then(res => {
        this.incomes = res.data
        this.show_withdraws = false;
        this.show_incomes = true;
      })
      .catch(err => {
        console.error(err); 
      })
    }
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  },
  watch: {
    'form.amount':function(){
      if(this.form.amount <= this.balance && this.form.amount >= 50000){
        this.validation = 'is-valid';
      }else{
        this.validation = 'is-invalid';
      }
    }
  },
}
</script>
