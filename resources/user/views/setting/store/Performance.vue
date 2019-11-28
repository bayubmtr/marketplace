<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h5 class="lead">PERFORMA TOKO</h5>
        <b-row>
          <b-col cols="3">
            Jumlah Transaksi Masuk
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.store_statistic.order}}
          </b-col>
          <b-col cols="3">
            Jumlah Transaksi Diterima
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.store_statistic.order_accept}}
          </b-col>
          <b-col cols="3">
            Jumlah Penilaian
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.store_statistic.review_count}}
          </b-col>
          <b-col cols="3">
            Rata-rata penilaian
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.store_statistic.review_avg}}
          </b-col>
          <b-col cols="3">
            Jumlah Produk
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.store_statistic.product_count}}
          </b-col>
          <b-col cols="3">
            Jumlah Pengikut
            <div class="dropdown-divider"></div>
          </b-col>
          <b-col cols="9">
            :  {{stores.follower_count}}
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Promo',
  data () {
    return {
      stores : {}
    }
  },
  mounted() {
    this.getStore();
  },
  methods: {
    getStore() {
      axios.get('/api/user/seller/stores')
      .then(res => {
        this.stores = res.data
      })
      .catch(err => {
      })
    },
  }
}
</script>
