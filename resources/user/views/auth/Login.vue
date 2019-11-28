<template>
    <div class="animated fadeIn">
        <div id="logreg-forms">
        <form @submit.prevent="submit" class="form-signin">
            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Masuk</h1>
            <div class="row justify-content-center">
                <b-button v-on:click="getRedirect('facebook')" variant="facebook" class="mr-1 btn-brand"><i class="fa fa-facebook"></i><span>Dengan Facebook</span></b-button>
                <b-button v-on:click="getRedirect('google')" variant="google-plus" class="mr-1 btn-brand"><i class="fa fa-google-plus"></i><span>Dengan Google+</span></b-button>
            </div>
            <p style="text-align:center" class="mt-2"> Atau  </p>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" v-model="loginForm.email" class="form-control" placeholder="Alamat Email" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" v-model="loginForm.password" class="form-control" placeholder="Kata Sandi" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <button class="btn btn-success btn-block"><i class="fa fa-sign-in"></i> Masuk</button>
            <a href="/auth/forgot-password" id="forgot_pswd">Lupa Kata Sandi ?</a>
            <hr>
            <router-link to="/auth/register">
                <button class="btn btn-primary btn-block" type="button" id="btn-signup"><i class="fa fa-user-plus"></i> Daftar Akun Baru</button>
            </router-link>
            </form>
        </div>
    </div>
</template>
<script>
import helper from '../../services/helper'
import toastr from 'toastr'

export default {
  name: 'LoginForm',
  data() {
      return {
          loginForm: {
              email: 'user3@gmail.com',
              password: '123123'
          },
          loginFormError: {
              name: 'true',
              email: 'true',
              password_confirmation: 'true',
              password: 'true'
          },
      }
  },
  components: {
      toastr
  },
  mounted(){
    this.notification();
    this.Check();
  },
  methods: {
      submit(e){
          axios.post('/api/user/login', this.loginForm).then(response =>  {
              localStorage.setItem('auth_token',response.data.token);
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');
              toastr['success']('Berhasil Login');
              this.setUserProfile();
              if(this.$route.query.redirect){
                this.$router.push(this.$route.query.redirect)
                location.reload();
              }else{
                  this.$router.push('/')
                  location.reload();
              }
          }).catch(function (error) {
            toastr['error'](error.response.data.message);
        });
      },
      Check(){
      return helper.check().then(response => {
        if(response == true){
          this.auth = 1;
          this.setUserProfile();
        } if(response == false){
          this.auth = 2
        }
      })
      },
      getAuthUser(name){
        return this.$store.getters.getAuthUser(name);
      },
      setUserProfile(){
    },
    getRedirect(data){
        if(data == 'facebook'){
            window.open('/auth/social/facebook');
        }
        if(data == 'google'){
            window.open('/auth/social/google');
        }
        
    }
  }
}
</script>