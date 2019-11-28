<template>
  <div class="app">
    <AppHeader fixed>
      <div class="header-atas">
        <div class="container tinggi-header-atas">
          <div class="tinggi-header-atas">
            <div class="row tinggi-header-atas">
              <div class="col">
                <span class="quote float-left align-middle">Jual Beli Online Itu Mudah</span>
              </div>
              <div class="col">
                <div class="navbar-nav float-right tinggi-header-atas">
                <a class="nav-item nav-link spasi" href="/seller/product">Jual Produk</a>
                <a class="nav-item nav-link spasi" href="/promo">Promo</a>
                <a class="nav-item nav-link spasi" href="#">Pusat Bantuan</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="header-bawah">
        <div class="container">
          <div class="d-flex justify-content-between bg-secondary header-bawah">
            <div class="p-2 byx-box" style="width:180px">
              <router-link to="/">
                <img class="byxmart mouse-pointer" :src="'/images/tokolan.png'" alt="zzz" >
              </router-link>
            </div>
            <div class="p-2 tengah align-self-center flex-fill">
              <div class="searchbox">
                <b-input-group>
                      <b-form-input v-on:keyup.enter="searchProduct()" id="elementsAppendButton" placeholder="Cari Produk" v-model="search" type="text"></b-form-input>
                      <b-input-group-append>
                        <b-button v-on:click="searchProduct()" variant="danger">Cari</b-button>
                      </b-input-group-append>
                </b-input-group>
              </div>
            </div>
            <div class="p-2 my-auto">
              <b-navbar-nav class="nav-bawah" v-if="auth == 1">
                <b-nav-item to="/cart">
                  <i class="fa fa-shopping-cart fa-lg"></i>
                  <b-badge v-show="notifications.cart_count" pill variant="danger">{{notifications.cart_count}}</b-badge>
                </b-nav-item>
                <b-nav-item to="/purchase">
                  <i class="fa fa-exchange fa-lg"></i>
                  <b-badge v-show="notifications.order_count" pill variant="danger">{{notifications.order_count}}</b-badge>
                </b-nav-item>
                <b-nav-item to="/message" class="d-none d-sm-block">
                  <i class="fa fa-envelope fa-lg"></i>
                  <b-badge v-show="notifications.message_count" pill variant="danger">{{notifications.message_count}}</b-badge>
                </b-nav-item>
                <div class="d-none d-sm-block">
                <HeaderDropdownAccnt/>
                </div>
              </b-navbar-nav>
              
              <b-navbar-nav class="nav-bawah" v-if="auth == 2">
                <b-nav-item to="/auth/register" >
                  <span class="mr-3 ml-4">Daftar</span>
                </b-nav-item>
                <b-nav-item to="/auth/login" >
                  <button type="button" class="btn btn-outline-dark">Masuk</button>
                </b-nav-item>
              </b-navbar-nav>
            </div>
          </div>
        </div>
      </div>
    </AppHeader>   
    <div class="app-body container">
      <main class="main">
          <router-view></router-view>
      </main>
    </div>
    <Footer/>
  </div>
</template>

<script>
import HeaderDropdownAccnt from './AccountDropdown'
import LoginDropdown from './LoginDropdown'
import Footer from './Footer'
import helper from '../services/helper'
import { Header as AppHeader } from '@coreui/vue'

export default {
  name: 'DefaultContainer',
  components: {
    Footer,
    LoginDropdown,
    AppHeader,
    HeaderDropdownAccnt
  },
  data () {
    return {
      auth : '',
      search : this.$route.query.search,
      notifications : {}
    }
  },
  mounted() {
    this.Check();
  },
  created() {
    Vue.bus.$on('update-notifications', this.getNotifications)
  },
  beforeDestroy(){
    this.$bus.$off('update-notifications');
  },
  methods: {
    logout(){
      helper.logout().then(() => {
          this.$store.dispatch('resetAuthUserDetail');
          this.$router.replace('/')
      })
    },
    getNotifications(){
      axios.get('/api/user/notifications')
      .then(res => {
        this.notifications = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
    searchProduct(){
      this.$router.push({ path: '/products', query: { search: this.search } })
    },
    Check(){
      return helper.check().then(response => {
        if(response == true){
          this.auth = 1;
          this.getNotifications();
          window.setInterval(() => {
              this.getNotifications();
          },10000);
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
        if(!this.getAuthUser('email')){
          helper.authUser().then(response => {
            console.log(response)
              this.$store.dispatch('setAuthUserDetail',{
              id : response.id,
              store_id : response.store_id,
              name: response.name,
              email: response.email,
              avatar:response.avatar
              });
          });
        }
    }
  },
  computed: {
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched.filter((route) => route.name || route.meta.label )
    }
  }
}
</script>
