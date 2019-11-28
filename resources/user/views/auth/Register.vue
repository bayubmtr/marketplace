<template>
    <div class="animated fadeIn">
        <div id="logreg-forms">
            <form @submit.prevent="submit" class="form-signup">
                
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center">Daftar Sekarang</h1>
                <input type="text" v-model="registerForm.first_name" id="nick-name" :class="'form-control '+registerFormError.first_name" placeholder="Nama Depan" required="" autofocus="">
                <input type="text" v-model="registerForm.last_name" id="user-name" :class="'form-control '+registerFormError.last_name" placeholder="Nama Belakang (optional)" required="" autofocus="">
                <input type="email" v-model="registerForm.email" id="user-email" :class="'form-control '+registerFormError.email" placeholder="Alamat Email" required autofocus="">
                <input type="password" v-model="registerForm.password" id="user-pass" :class="'form-control '+registerFormError.password" placeholder="Kata Sandi" required autofocus="">
                <input type="password" v-model="registerForm.password_confirmation" id="user-repeatpass" :class="'form-control '+registerFormError.password_confirmation" placeholder="Ulangi Kata Sandi" required autofocus="">

                <button class="btn btn-primary btn-block mt-3"><i class="fa fa-user-plus"></i> Daftar</button>
                <a href="/auth/login" id="cancel_signup"><i class="fa fa-angle-left"></i> Kembali ke halaman Login</a>
            </form>
        </div>
    </div>
</template>
<script>
import helper from '../../services/helper'
import toastr from 'toastr'

export default {
  name: 'RegisterForm',
  data() {
      return {
        registerForm: {
            email: '',
            password: '',
            password_confirmation: '',
            first_name: '',
            last_name: '',
        },
        registerFormError: {
              first_name: '',
              last_name: '',
              email: '',
              password_confirmation: '',
              password: ''
          },
      }
  },
  components: {
      toastr
  },
  mounted(){
    this.notification();
  },
  methods: {
      submit(e){
            this.registerFormError.first_name = ''
            this.registerFormError.last_name = ''
            this.registerFormError.email = ''
            this.registerFormError.password = ''
            this.registerFormError.password_confirmation = ''
                axios.post('/api/user/register', this.registerForm).then(response =>  {
                    toastr['success']('Berhasil mendaftar !');
                    this.$router.push('/auth/login');
                }).catch(error => {
            
            for(var i in error.response.data.message){
                if(i == 'email'){
                    this.registerFormError.email = 'is-invalid'
                    toastr['error']("Email salah atau sudah digunakan");
                }
                if(i == 'name'){
                    this.registerFormError.first_name = 'is-invalid'
                    toastr['error']("Masukan Nama depan");
                }
                if(i == 'password_confirmation'){
                    this.registerFormError.password_confirmation = 'is-invalid'
                    toastr['error']("Konfirmasi Password tidak tepat");
                }
                if(i == 'password'){
                    this.registerFormError.password = 'is-invalid'
                    toastr['error']("Masukan password yang benar");
                }
            }
            })
        }
      
  }
}
</script>