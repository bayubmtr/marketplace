<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col cols="10" class="text-center">
        <b-card v-for="(item, index) in promos" :key="index">
          <router-link :to="'/promo/'+item.code">
            <h6>{{item.title}}</h6>
            <img :src="item.image" alt="">
          </router-link>
        </b-card>
        <b-card v-if="!promos">
          <h4>Belum Ada Promo Untuk Saat Ini</h4>
        </b-card>
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
      promos : {}
    }
  },
  mounted() {
    this.getSlideshow()
  },
  methods: {
    getSlideshow(){
      axios.get('/api/user/promos')
      .then(res => {
        this.promos = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    }
  }
}
</script>