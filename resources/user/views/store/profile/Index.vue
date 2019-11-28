<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col>
          <Profile :user="storeProfile"/>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
          <About :slideshow="storeProfile.slideshow" :description="storeProfile.description" chartId="card-chart-01" class="store-about"/>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
          <Product :storeId="storeProfile.id" class="store-product"/>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import helper from '../../../services/helper'
import Product from './StoreProduct'
import Profile from './StoreProfile'
import About from './StoreAbout'

export default {
  name: 'index',
  components: {
    Product,
    Profile,
    About,
  },
  data: function () {
    return {
      store_id : this.$route.params.store_url,
      catid : this.$route.params.catid,
      storeProfile : {},
      storeProduct : {},
    }
  },
  mounted() {
    this.getStore();
  },
  methods: {
    getStore(){
      axios.get('/api/user/stores/'+this.store_id)
      .then(res => {
        this.storeProfile = res.data;
        if(res.data.status == 404){
          this.$router.push('/Pages/404') 
        }
      })
      .catch(err => {
        console.error(err); 
      })
    },
  }
}
</script>