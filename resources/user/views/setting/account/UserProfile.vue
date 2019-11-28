<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Profil Saya</span><br><br>
        <b-card>
          <b-row>
            <b-col cols="2" class="text-left">
              <p class="text-sedang form-label">Nama depan</p>
              <p class="text-sedang form-label">Nama belakang</p>
              <p class="text-sedang form-label">Tanggal lahir </p>
              <p class="text-sedang form-label">No Hp</p>
              <p class="text-sedang form-label">Jenis Kelamin</p>
            </b-col>
            <b-col cols="10">
              <b-form-group>
              <input :disabled="edit === false" v-model="account.first_name" type="text" placeholder="" class="form-control"><br>
              <input :disabled="edit === false" v-model="account.last_name" type="text" placeholder="" class="form-control"><br>
              <datepicker class="pb-2" v-model="account.date_of_birth" :disabled="edit === false"></datepicker><br>
              <input :disabled="edit === false" v-model="account.phone_number" type="text" placeholder="" class="form-control"><br>
              <b-form-radio-group class="pt-3">
                <b-form-radio :disabled="edit === false" v-model="account.gender" name="some-radios" value="M">Laki-laki</b-form-radio>
                <b-form-radio :disabled="edit === false" v-model="account.gender" name="some-radios" value="F">Perempuan</b-form-radio>
              </b-form-radio-group>
              </b-form-group>
              <b-button :disabled="edit == true" v-on:click="edit = true" variant="primary">Ubah</b-button>
              <b-button :disabled="edit == false" v-on:click="updateAccount()" variant="primary">Simpan</b-button>
            </b-col>
          </b-row>
        </b-card>
      </b-card>
    </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {
  name: 'Promo',
  components: {
    Datepicker
  },
  data () {
    return {
      account : {
        user_id : '',
        first_name : '',
        last_name : '',
        date_of_birst : '',
        gender : '',
        phone_number : '',
        type : 'profile'
      },
      save : '',
      edit : false,
    }
  },
  mounted() {
    this.getAccount();
  },
  methods: {
    getAccount(){
      axios.get('/api/user/accounts')
      .then(res => {
        this.account.user_id = res.data.profile.user_id,
        this.account.first_name = res.data.profile.first_name,
        this.account.last_name = res.data.profile.last_name,
        this.account.date_of_birth = res.data.profile.date_of_birth,
        this.account.gender = res.data.profile.gender,
        this.account.phone_number = res.data.profile.phone_number
      })
      .catch(err => {
        console.error(err); 
      })
    },
    updateAccount(){
      axios.patch('/api/user/accounts/'+this.account.user_id,this.account)
      .then(res => {
        this.edit = false
        toastr.success('Bershasil Mengubah Profil')
      })
      .catch(err => {
        toastr.error('Terjadi Masalah !')
      })
    }
  }
}
</script>
