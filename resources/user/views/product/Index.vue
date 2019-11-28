<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm=2>
        <Sidebar chartId="card-chart-01" class="storeproduct-sidebar"/>
      </b-col>
      <b-col sm=10>
        <Product v-on:searchProduct="searchProduct()" :filter="filterProduct" :products="products" chartId="card-chart-01" class="storeprouduct-product"/>
        <div class="row">
          <div class="col-md-8">
              <pagination :data="products" :limit=3 v-on:pagination-change-page="searchProduct"></pagination>
          </div>
          <div class="col-md-4">
            <div class="float-right">
              <select name="pageLength" class="form-control" v-model="filterProduct.pageLength" @change="searchProduct" v-if="products.total">
                <option value="5">5 per page</option>
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="100">100 per page</option>
              </select>
            </div>
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'
import helper from '../../services/helper'
import Product from './Product'
import Sidebar from './Sidebar'

export default {
  name: 'index',
  components: {
    Product,
    Sidebar,
    pagination
  },
  data: function () {
    return {
      catid : this.$route.params.catid,
      search : this.$route.query.key,
      products : {},
      filterProduct : {
        sortBy : this.$route.query.sortBy,
        order: this.$route.query.order,
        pageLength: 10,
        search : this.$route.query.search,
        cat : this.$route.query.cat,
        rate : this.$route.query.rate,
        minPrice : this.$route.query.minPrice,
        maxPrice : this.$route.query.maxPrice,
        page : this.$route.query.page,
        location : this.$route.query.location,
      }
    }
  },
  mounted() {
    this.searchProduct();
  },
  methods: {
    searchProduct(page){
      if (typeof page === 'undefined') {
                    page = 1;
                }
      let url = helper.getFilterURL(this.filterProduct);
      axios.get('/api/user/products'+'?page='+page+url)
      .then(res => {
        this.products = res.data;
      })
      .catch(err => {
        console.error(err); 
      })
    }
  },
  watch: {
    '$route' (to, from) {
      this.filterProduct.sortBy = this.$route.query.sortBy,
      this.filterProduct.order = this.$route.query.order,
      this.filterProduct.search = this.$route.query.search,
      this.filterProduct.cat = this.$route.query.cat,
      this.filterProduct.rate = this.$route.query.rate,
      this.filterProduct.minPrice = this.$route.query.minPrice,
      this.filterProduct.maxPrice = this.$route.query.maxPrice,
      this.filterProduct.page = this.$route.query.page;
      this.filterProduct.location = this.$route.query.location;
      this.searchProduct();
    },
  }
}
</script>
