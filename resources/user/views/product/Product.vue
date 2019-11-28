<template>
  <div class="animated fadeIn">
    <b-row align-h="between" class="storeproduct-list">
      <b-col style="padding-left:5px; padding-right:5px;">
        <b-card>
          <b-row>
            <b-col>
              <span>Urutkan : </span>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active" v-if="filter.sortBy == 'pop'">
                  <input v-on:click="SortBy('pop')" type="radio" name="options" id="option1" autocomplete="off"> Terpopuler
                </label>
                <label class="btn btn-secondary" v-if="filter.sortBy != 'pop'">
                  <input v-on:click="SortBy('pop')" type="radio" name="options" id="option1" autocomplete="off"> Terpopuler
                </label>
                <label class="btn btn-secondary active" v-if="filter.sortBy == 'ctime'">
                  <input v-on:click="SortBy('ctime')" type="radio" name="options" id="option2" autocomplete="off"> Terbaru
                </label>
                <label class="btn btn-secondary" v-if="filter.sortBy != 'ctime'">
                  <input v-on:click="SortBy('ctime')" type="radio" name="options" id="option2" autocomplete="off"> Terbaru
                </label>
                <label class="btn btn-secondary active" v-if="filter.sortBy == 'sales'">
                  <input v-on:click="SortBy('sales')" type="radio" name="options" id="option3" autocomplete="off"> Terlaris
                </label>
                <label class="btn btn-secondary" v-if="filter.sortBy != 'sales'">
                  <input v-on:click="SortBy('sales')" type="radio" name="options" id="option3" autocomplete="off"> Terlaris
                </label>
              </div>
            </b-col>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
    <b-alert v-if="search" show variant="secondary">Hasil Pencarian Untuk <strong>{{search}}</strong> ada {{products.total}} Produk</b-alert>
    <b-row class="storeproduct-list">
      <b-col class="product-list col-xs-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2" v-for="data in products.data" :key="data.id">
        <router-link class="product-click" :to="'/'+data.store_url+'/'+data.id+'/'+slug(data.name)">
          <b-card body-class="card-description text-truncate-2" :img-src="data.photo.medium" img-alt="Img" img-top>
            <div class="product-title text-truncate">
              <span>
                {{data.name}}
              </span>
            </div>
            <p class="product-price">
              {{data.price | formatRupiah}}
            </p>
            <p class="product-from text-truncate">
              {{data.location}}
            </p>
            <div slot="footer">
              <span class="fa fa-heart align-middle"> {{ data.favorite_count }}</span>
              <span class="float-right my-auto">
              <star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" v-bind:rating="toInteger(data.review_avg)" v-bind:star-size="16"></star-rating>
              {{data.review_count}}</span>
            </div>
          </b-card>
        </router-link>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import helper from '../../services/helper'
export default {
  props: [
    'products',
    'filter'
  ],
  name: 'Product',
  data: function () {
    return {
      show: true,
      userid : this.$route.params.store_url,
      search : this.$route.query.search,
      page: '',
      order: [{ text: 'Urutkan', value: null }, 'Murah ke Mahal', 'Mahal ke Murah'],
    }
  },
  methods: {
    SortBy(sortby){
      this.$router.push({ query: Object.assign({}, this.$route.query, { sortBy: sortby }) })
    },
    orderBy(){
      this.$router.push({ query: Object.assign({}, this.$route.query, { order: this.filter.order}) })
    },
    toInteger: function (value) {
      return parseFloat(value, 1);
    },
    slug(value){
      return helper.toSlug(value);
    }
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  },
  watch: {
    page: function(page){
      this.$router.push({ query: Object.assign({}, this.$route.query, { page: this.page}) })
    }
  }
}
</script>
