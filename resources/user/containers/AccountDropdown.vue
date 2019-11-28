<template>
  <AppHeaderDropdown right no-caret>
    <template slot="header">
      <i class="fa fa-user-o fa-lg"/>
    </template>
    <template slot="dropdown">
      <b-dropdown-item to="/setting/user-profile"><i class="fa fa-bell-o" /> Profil
      </b-dropdown-item>
      <b-dropdown-item to="/purchase"><i class="fa fa-envelope-o" /> Pembelian
      </b-dropdown-item>
      <b-dropdown-item to="/sale"><i class="fa fa-envelope-o" /> Pejualan
      </b-dropdown-item>
       <b-dropdown-item to="/seller/product"><i class="fa fa-envelope-o" /> Produk
      </b-dropdown-item>
      <b-dropdown-item to="/following"><i class="fa fa-envelope-o" /> Toko Langganan
      </b-dropdown-item>
      <b-dropdown-item to="/favorite_product"><i class="fa fa-tasks" /> Produk Wishlist
      </b-dropdown-item>
      <b-dropdown-item @click.prevent="logout"><i class="fa fa-lock" /> Keluar</b-dropdown-item>
    </template>
  </AppHeaderDropdown>
</template>

<script>
import { HeaderDropdown as AppHeaderDropdown } from '@coreui/vue'
import helper from '../services/helper'
export default {
  name: 'DefaultHeaderDropdownAccnt',
  components: {
    AppHeaderDropdown
  },
  data: () => {
    return { itemsCount: 42 }
  },
  methods : {
    logout(){
      helper.logout().then(() => {
          this.$store.dispatch('resetAuthUserDetail');
          this.$router.replace('/')
          location.reload()
      })
    },
  }
}
</script>
