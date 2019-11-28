<template>
  <b-nav-item-dropdown right no-caret>
        <template slot="button-content">
          <button @mouseover="LoginForm" type="button" class="btn btn-outline-dark">Masuk</button>
        </template>
        <b-dropdown-header tag="div" class="text-center login-dropdown"><strong>Login</strong></b-dropdown-header>
          <b-form @submit.prevent="submit">
            <b-input-group class="mb-3">
            <b-input-group-prepend>
              <b-input-group-text>@</b-input-group-text>
            </b-input-group-prepend>
            <b-form-input type="text" class="form-control" placeholder="Email" autocomplete="email" v-model="loginForm.email"/>
          </b-input-group>
          <b-input-group class="mb-3">
            <b-input-group-prepend>
              <b-input-group-text><i class="fa fa-lock"></i></b-input-group-text>
            </b-input-group-prepend>
            <b-form-input type="password" class="form-control" placeholder="Password" autocomplete="password" v-model="loginForm.password"/>
          </b-input-group>
            <b-row>
              <b-col cols="12">
                <b-dropdown-item>
                <b-button variant="primary" type="submit" v-on:click="submit" block>Login</b-button>
                </b-dropdown-item>
              </b-col>
            </b-row>
          </b-form>
          <b-card-body>
                <b-row>
                  <b-button variant="facebook" class="mr-1 btn-brand"><i class="fa fa-facebook"></i><span>Facebook</span></b-button>
                  <b-button variant="google-plus" class="mr-1 btn-brand"><i class="fa fa-google-plus"></i><span>Google+</span></b-button>
                </b-row>
              </b-card-body>
      </b-nav-item-dropdown>
</template>

<script>
import helper from '../services/helper'
import toastr from 'toastr'

export default {
  name: 'LoginDropdown',
  data() {
      return {
          loginForm: {
              email: 'user3@gmail.com',
              password: '123123'
          },
          showLoginForm : false
      }
  },
  components: {
      toastr
  },
  mounted(){
    this.notification();
    this.setUserProfile();
  },
  methods: {
      submit(e){
          axios.post('/api/user/login', this.loginForm).then(response =>  {
              localStorage.setItem('auth_token',response.data.token);
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');
              toastr['success'](response.data.message);
              this.setUserProfile();
              this.$router.push('/home')
              location.reload()
          }).catch(error => {
              toastr['error'](error.response.data.message);
          });
      },
      notification(){
          toastr.options = {
              "positionClass": "toast-top-right"
          };
          $('[data-toastr]').on('click',function(){
              var type = $(this).data('toastr'),message = $(this).data('message'),title = $(this).data('title');
              toastr[type](message, title);
          });
      },
      getAuthUser(name){
                return this.$store.getters.getAuthUser(name);
      },
      setUserProfile(){
          this.$emit('setUserProfile');
      },
      loginForm(){
        this.showLoginForm = !this.showLoginForm;
      }
      
  }
}
</script>