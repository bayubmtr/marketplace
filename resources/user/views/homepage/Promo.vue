<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-row>
        <b-col cols="8">
          <div>
            <b-carousel id="carousel1"
                        style="text-shadow: 1px 1px 2px #333;"
                        controls
                        indicators
                        background="#ababab"
                        :interval="4000"
                        img-width="1024"
                        img-height="480"
                        v-model="slide"
            >
              <!-- Text slides with image -->
              <router-link target="_blank" :to="'/promo/'+item.code" v-for="(item, index) in slideshows" :key="index">
                <b-carousel-slide  
                  :img-src="item.image"
                ></b-carousel-slide>
              </router-link>
            </b-carousel>
          </div>
        </b-col>
        <b-col cols="4" class="promo-kanan">
        <img v-if="slideshows[0]" :src="slideshows[0].image" alt="image slot">
        <img v-if="slideshows[2]" :src="slideshows[1].image" alt="image slot">
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Promo',
  data () {
    return {
      slide: 0,
      sliding: null,
      slideshows : {}
    }
  },
  mounted() {
    this.getSlideshow();
  },
  methods: {
    getSlideshow(){
      axios.get('/api/user/promos')
      .then(res => {
        this.slideshows = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    }
  }
}
</script>
