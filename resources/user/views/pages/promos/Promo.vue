<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col cols="10">
        <div>
          <b-card>
            <div v-if="promo" class="text-center">
              <h4>{{promo.title}}</h4>
              <b-row>
                <b-col cols="6" class="text-center">
                  <img :src="promo.image" alt="">
                </b-col>
                <b-col cols="6" class="text-center">
                  <h1 class="align-middle">{{promo.code}}</h1>
                </b-col>
              </b-row>
              <div style="white-space: pre;">{{promo.content}}</div>
            </div>
          </b-card>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>

export default {
  name: 'Promo',
  components: {
  },
  data: function () {
    return {
      promo : {},
      code : this.$route.params.promo_id,
    }
  },
  mounted() {
    this.getPromo();
  },
  methods: {
    getPromo(){
      axios.get('/api/user/promo/'+this.code)
      .then(res => {
        this.promo = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    }
  }
}
</script>