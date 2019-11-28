<template>
  <div class="animated fadeIn">
    <b-row class="product-list mb-2">
      <template v-if="products.total != 0">
      <b-col cols="2" class="product-grid" v-for="data in products.data" :key="data.id">
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
      </template>
      <template v-else>
         <b-col>
            <div class="alert alert-danger text-center" role="alert">
            <strong>Toko ini belum memiliki produk !</strong>
          </div>
         </b-col>
      </template>
    </b-row>
    <div class="text-center">
      <i v-if="scroll" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
    </div>
  </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'
import helper from '../../../services/helper'
export default {
  props: [
    'storeId'
  ],
   components : { pagination},
  name: 'Product',
  data: function () {
    return {
      products : {},
      scroll : false,
      filterProduct : {
        sortBy : '',
        order: '',
        store_id : '',
        pageLength: 6,
      }
    }
  },
  mounted(){
    this.scrollBot();
  },
  methods: {
    getProduct(page){
      this.filterProduct.store_id = this.storeId;
      if (typeof page === 'undefined') {
                    page = 1;
                }
      let url = helper.getFilterURL(this.filterProduct);
      axios.get('/api/user/products'+'?page='+page+url)
      .then(res => {
        if(!this.products.data){
           this.products = res.data
        }
        else{
          this.products.data.push(...res.data.data)
          this.products.next_page_url = res.data.next_page_url
          this.products.current_page = res.data.current_page
          this.scroll = false
        }
      })
      .catch(err => {
        console.error(err); 
      })
    },
    scrollBot() {
      window.onscroll = () => {
        let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

        if (bottomOfWindow) {
        if(this.products.current_page != this.products.last_page){
          this.scroll = true
          this.getProduct(this.products.current_page+1);
        }
        }
    }
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
    }
  },
  watch: {
    storeId(){
      this.getProduct();
    }
  },
}
</script>