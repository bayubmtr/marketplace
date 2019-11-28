<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col cols=2 class="p-0 ">
        <b-card>
        <h5 class="text-besar">PEMBELIAN</h5>
        <b-nav vertical>
        <b-nav-item to="/purchase">Semua Pembelian</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="" to="/purchase/wait-payment">Belum Dibayar</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/purchase/seller-processed">Diproses Penjual</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/purchase/shipping">Sedang dikirim</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/purchase/finish">Selesai</b-nav-item>
        <div class="dropdown-divider"></div><br>
        
        <h5 class="text-besar pt-3">PENJUALAN</h5>
        <b-nav-item class="text-muted text-kecil" to="/sale">Semua Penjualan</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/sale/new">Pesanan Baru</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/sale/to-ship">Belum Dikirim</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/sale/shipping">Sedang Dikirim</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="text-muted text-kecil" to="/sale/finish">Selesai</b-nav-item>
        <div class="dropdown-divider"></div>
        </b-nav>
      </b-card>
      </b-col>
      <b-col cols=10>
            <router-view></router-view>
      </b-col>
    </b-row>
  </div>
</template>

<script>

export default {
  name: 'index',
  components: {
  },
  data: function () {
    return {
    }
  },
  methods: {
    
  }
}
</script>