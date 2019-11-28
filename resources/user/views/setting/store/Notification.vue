<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h5 class="lead">TOKO</h5>
        <p class="sidebar-menu">Profil Toko</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Jasa Kirim</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Alamat</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Review</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Performa</p>
        <div class="dropdown-divider"></div>
        <br>
        <h5 class="lead">PENGATURAN</h5>
        <p class="sidebar-menu">Pengaturan Toko</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Pengaturan Notifikasi</p>
        <div class="dropdown-divider"></div>
        <br>
        <h5 class="lead">AKUN</h5>
        <p class="sidebar-menu">Akun Saya</p>
        <div class="dropdown-divider"></div>
        <p class="sidebar-menu">Ubah Password</p>
        <div class="dropdown-divider"></div>
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
