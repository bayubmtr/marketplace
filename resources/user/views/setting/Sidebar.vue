<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h5 class="lead">AKUN</h5>
        <b-nav vertical>
          <b-nav-item to="/setting/user-profile">Profil</b-nav-item>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/user-account">Password</b-nav-item>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/user-bank">Bank</b-nav-item>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/user-wallet">Dompet</b-nav-item>
          <div class="dropdown-divider"></div>
          <h5 class="lead pt-4">Alamat</h5>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/address">Daftar Alamat</b-nav-item>
          <h5 class="lead pt-4">TOKO</h5>
          <b-nav-item to="/setting/store/profile">Profil Toko</b-nav-item>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/store/logistic">Jasa Kirim</b-nav-item>
          <div class="dropdown-divider"></div>
          <b-nav-item to="/setting/store/performance">Performa Toko</b-nav-item>
          <div class="dropdown-divider"></div>
        </b-nav>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Promo',
  data () {
    return {
      slide: 0,
      sliding: null
    }
  },
  methods: {
    onSlideStart (slide) {
      console.log('onSlideStart', slide)
      this.sliding = true
    },
    onSlideEnd (slide) {
      console.log('onSlideEnd', slide)
      this.sliding = false
    }
  }
}
</script>
