<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Profil Saya</span><br><br>
        <b-card>
          <b-alert
            variant="success"
            dismissible
            fade
            :show="showAllert"
          >
            Berhasil Mengganti Password !
          </b-alert>
          <b-row>
            <b-col cols="3" class="text-left">
              <p class="text-sedang form-label">Email</p>
              <p class="text-sedang form-label">Password</p>
              <p class="text-sedang form-label">Password Baru</p>
              <p class="text-sedang form-label">Konfirmasi Password Baru</p>
            </b-col>
            <b-col cols="9">
              <b-form-group>
                <b-form-input 
                  :disabled="true" 
                  v-model="account.email" 
                  type="text" placeholder="Email" class="form-control"
                ></b-form-input><br>
                <b-form-input 
                  :disabled="edit === false" 
                  v-model="account.password"
                   type="text" placeholder="Password sekarang" class="form-control"
                ></b-form-input>
                <b-form-invalid-feedback v-if="errors.password">
                  Password tidak memenuhi syarat
                </b-form-invalid-feedback><br v-else>
                <b-form-input
                  :disabled="account.password.length > 7 ? false : true"
                  id="input-password"
                  v-model="account.new_password"
                  :state="passwordState"
                  aria-describedby="input-password-help input-password-feedback"
                  placeholder="Password Baru"
                  trim
                ></b-form-input>
                <b-form-invalid-feedback id="input-password-feedback">
                  Mininal 8 karakter
                </b-form-invalid-feedback><br>
                <b-form-input
                  :disabled="!passwordState"
                  id="input-confirm_password"
                  v-model="account.confirm_new_password"
                  :state="confirmPasswordState"
                  aria-describedby="input-confirm_password-help input-confirm_password-feedback"
                  placeholder="Konfirmasi Password Baru"
                  trim
                ></b-form-input>
                <b-form-invalid-feedback v-if="account.confirm_new_password">
                  Tidak sama
                </b-form-invalid-feedback><br>
                <b-button :disabled="edit == true" v-on:click="edit = true" variant="primary">Ubah</b-button>
              <b-button :disabled="edit == false" v-on:click="updateAccount()" variant="primary">Simpan</b-button>
              </b-form-group>
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
        email : '',
        password : '',
        new_password : '',
        confirm_new_password : '',
        type : 'password'
      },
      edit : false,
      showAllert : false,
      errors : {
        email : '',
        password : '',
      }
    }
  },
  mounted() {
    this.getAccount();
  },
  methods: {
    getAccount(){
      axios.get('/api/user/accounts')
      .then(res => {
        this.account.email = res.data.email,
        this.account.user_id = res.data.id
      })
      .catch(err => {
        
      })
    },
    updateAccount(){
      axios.patch('/api/user/accounts/'+this.account.user_id,this.account)
      .then(res => {
        this.edit = false
        this.showAllert = true
        toastr.success('Berhasil Mengubah Profil')
      })
      .catch(err => {
        toastr.error(err.response.data.message)
        this.errors.email = err.response.data.errors.email
        this.errors.password = err.response.data.errors.password
      })
    }
  },
  computed: {
    passwordState() {
      if(this.account.new_password.length >= 1){
        return this.account.new_password.length > 7 ? true : false
      }
    },
    confirmPasswordState() {
      if(this.account.confirm_new_password.length > 7){
        return this.account.confirm_new_password == this.account.new_password ? true : false
      }else if(this.account.confirm_new_password.length >= 1){
        return false
      }
    }
  }
}
</script>
