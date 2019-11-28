<template>
  <div class="animated fadeIn">
    <b-row align-h="between">
        <b-col cols="4"><h5>Produk Terlaris</h5></b-col>
        <b-col cols="4"><a href="#" class="float-right no-gutters new-a"> <router-link to="/products?sortBy=sales">Lihat Lainnya</router-link> </a></b-col>
    </b-row>
    <b-row class="product-list mb-2">
      <b-col cols="2" class="product-grid" v-for="data in Products.data" :key="data.id">
        <router-link class="product-click" :to="'/'+data.store_url+'/'+data.id+'/'+Slug(data.name)">
          <b-card class="card-product mouse-pointer" body-class="card-description text-truncate border-top" :img-src="data.photo.medium" img-alt="Img" img-top>
            <div class="product-title">
              <span>
                {{ data.name }}
              </span>
            </div>
            <p class="product-price">
              {{ data.price | formatRupiah }}
            </p>
            <p class="product-from">
              {{data.location}}
            </p>
            <div>
              {{data.store_name}}
            </div>
            <div slot="footer">
              <span class="fa fa-heart align-middle mt-1"> {{ data.favorite }}</span>
              <span class="float-right mt-0">
              <star-rating v-if="data.review_count" :increment="0.1" class="star-rating star-custom my-auto" v-bind:show-rating="true" v-bind:inline="true" v-bind:read-only="true" v-bind:rating="toInteger(data.review_avg)" v-bind:star-size="18"></star-rating>
              </span>
            </div>
          </b-card>
        </router-link>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import helper from '../../services/helper'
import pagination from 'laravel-vue-pagination'
export default {
  name: 'ProductRecommend',
  data: function () {
    return {
      Products : {},
      show: true
    }
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      axios.get('/api/user/products?sortBy=sales&order=asc')
          .then(response => this.Products = response.data);
    },
    getProductDetail(store_url, product_id){
      router.push('/'+store_url+'/'+product_id+'/'+product_name)
    },
    toInteger: function (value) {
      return parseFloat(value, 1);
    },
    Slug(value){
      return helper.toSlug(value);
    }
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
  }
}
</script>
