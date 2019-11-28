<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <b-row class="justify-content-center">
        <b-col md="8">
          <b-card-group>
            <b-card no-body class="p-4">
              <b-card-body>
                <form @submit.prevent="submit">
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <div class="input-group mb-3">
                  <span class="input-group-addon"><i class="icon-user"></i></span>
                  <input type="text" class="form-control" name="email" placeholder="Email" v-model="loginForm.email">
                </div>
                <div class="input-group mb-4">
                  <span class="input-group-addon"><i class="icon-lock"></i></span>
                  <input type="password" class="form-control" placeholder="Password" v-model="loginForm.password">
                </div>
                <b-row>
                  <b-col cols="6">
                    <b-button variant="primary" class="px-4" type="submit">Login</b-button>
                  </b-col>
                </b-row>
              </form>
              </b-card-body>
            </b-card>
            <b-card no-body class="text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <b-card-body class="text-center align-middle">
                <div>
                  <h2 class="mt-5">BYXMART</h2>
                </div>
              </b-card-body>
            </b-card>
          </b-card-group>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
    import helper from '../../services/helper'


    export default {
        data() {
            return {
                loginForm: {
                    email: 'admin@gmail.com',
                    password: '123123'
                }
            }
        },
        components: {
        },
        mounted(){
          this.notification();
        },
        methods: {
            submit(e){
                axios.post('/api/admin/login', this.loginForm).then(response =>  {
                    localStorage.setItem('auth_token_admin',response.data);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token_admin');
                    toastr.success('Berhasil Login !');
                    this.setUserProfile();
                    this.$router.push('/admin/dashboard')
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
            setUserProfile(){
              if(!this.getAuthUser('email')){
                helper.authUser().then(response => {
                    this.$store.dispatch('setAuthUserDetail',{
                    first_name: response.profile.first_name,
                    last_name: response.profile.last_name,
                    email: response.user.email,
                    avatar:response.profile.avatar
                    });
                });
              }
            },
            getAuthUser(name){
              return this.$store.getters.getAuthUser(name);
            },
        }
    }
</script>
