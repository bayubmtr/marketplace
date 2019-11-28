<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col cols=2 class="p-0 ">
        <b-card>
        <router-link to="/seller/product/new">
        <b-button variant="outline-primary">Tambah Produk</b-button>
        </router-link>
        <h5 class="text-besar pt-4">Produk</h5>
        <b-nav vertical>
        <b-nav-item to="/seller/product">Semua Produk</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="" to="/seller/product?status=active">Dijual</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="" to="/seller/product?status=inactive">Diarsipkan</b-nav-item>
        <div class="dropdown-divider"></div>
        <b-nav-item class="" to="/seller/product/discussion">Diskusi Produk</b-nav-item>
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
  name: 'Index',
  components: {
  },
  data: function () {
    return {
      
    }
  },
  mounted() {
    this.cekStore();
  },
  methods: {
    cekStore(){
        axios.get('/api/user/seller/stores')
        .then(res => {
        })
        .catch(err => {
            this.$router.push("/setting/store/profile")
        })
    },
  }
}
</script>