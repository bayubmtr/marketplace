<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm="12">
            <Promo chartId="card-chart-01" class="promo"/>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
            <ProductRecommend chartId="card-chart-01" class="product"/>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
            <ProductRandom chartId="card-chart-01" class="product"/>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Promo from './Promo'
import ProductRecommend from './ProductRecommend'
import ProductRandom from './ProductRandom'

export default {
  name: 'dashboard',
  components: {
    Promo,
    ProductRecommend,
    ProductRandom
  },
  data() {
    return {
      
  }
  },
  mounted() {
    },
    methods: {
    }
}
</script>
