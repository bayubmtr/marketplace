<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-row class="container pr-0">
        <template  v-if="product_detail">
        <b-col cols="9">
          <b-row class="card">
            <b-col cols=12>
              <b-row>
                <b-col class="col-4 pb-2">
                  <b-row class="pr-1 pl-1 pt-2">
                    <div  class="product-detail-foto">
                      <span class="v-mid"></span>
                      <img v-if="product_detail.photos" @click="openLightBox" class="product-foto" :src="product_detail.photos[main_image].high" alt="">
                    </div>
                  </b-row>
                  <b-row class="pt-2 pr-1 pl-1">
                    <b-col cols="3" class="pr-1 pl-1"
                      v-for="(value, index) in product_detail.photos" 
                      style="display: inline-block" :key="index"
                    >
                    <div  class="img-zoom-container">
                      <img  class="foto-product-thumb mouse-pointer"
                        :src="value.medium" 
                        style="height: 100px" 
                        @click="selectImage(index)"
                      >
                    </div>
                    </b-col>
                  </b-row>
                </b-col>
                <b-col class="col-8" v-if="product_detail">
                  <h3 class="pt-3">{{product_detail.name}}</h3>
                    <span class="border-right  border-secondary pr-1" v-if="product_detail.review_statistic">
                      <star-rating class="star-rating my-auto" :increment="0.01" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" v-bind:rating="toInteger(product_detail.review_statistic.review_avg)" v-bind:star-size="16"></star-rating>
                    {{review_avg}}</span>
                    <span class="border-right  border-secondary pr-1 pl-1">
                      <strong>{{review_total}} </strong>Penilaian
                    </span>
                    <span class="border-right  border-secondary pr-1 pl-1">
                      <strong>{{product_detail.sold_count}} </strong>Terjual
                    </span>
                    <span class="pl-1">
                      <strong>{{product_detail.view}} </strong>Dilihat
                    </span>
                  <div class="pt-4">
                    <span class="text-kecil" v-if="product_detail.discount != 0"><s>{{product_detail.price | formatRupiah}}</s></span>
                    <span class="text-besar text-tebal detail-product-price">
                      {{product_detail.price*(100-product_detail.discount)/100 | formatRupiah}}
                    </span>
                    <span class="badge badge-danger" v-if="product_detail.discount != 0">Diskon {{product_detail.discount}}%</span>
                  </div>
                    <div class="mt-3">
                      <number-input v-model="product.quantity" :min="1" :max="product_detail.stock" size="small" center inline controls></number-input>
                    </div>
                  <span v-if="product.quantity == 0" class="text-danger">Barang kosong</span>
                  <div class="input-group mt-4">
                    <button v-on:click="AddToCart" :disabled="addToCartButton" type="button" class="btn btn-small btn-outline-primary mr-2"><i class="fa fa-shopping-cart"></i> Masukan Keranjang</button>
                    <button v-if="product_detail.wishlist == false" v-on:click="addToWishlist" type="button" class="btn btn-small btn-outline-primary mr-2"><i class="fa fa-heart-o"></i> Tambah Wishlist</button>
                    <button v-if="product_detail.wishlist == true" v-on:click="removeWishlist" type="button" class="btn btn-small btn-outline-primary mr-2"><i class="fa fa-heart"></i> Hapus Wishlist</button>
                    <button v-on:click="sendNewChat(product_detail.store_id)" type="button" class="btn btn-outline-primary mr-2"><i class="fa fa-comments-o"></i> Chat Penjual</button>
                  </div>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-tabs style="width:100%;">
              <b-tab>
                <template slot="title" class="border mr-3">
                  <div class="text-tebal text-sedang">Informasi Produk</div>
                </template>
                <information :product="product_detail"></information>
              </b-tab>
              <b-tab>
                <template slot="title" class="border mr-3">
                  <div class="text-tebal text-sedang">Penilaian Produk ({{star_total}})</div>
                </template>
                <review :review="product_detail.review" :store="product_detail.store_name" :star="product_detail.product_statistic"></review>
              </b-tab>
              <b-tab>
                <template slot="title" class="border mr-3">
                  <div class="text-tebal text-sedang">Diskusi Produk <span>({{total_discussion}})</span></div>
                </template>
                <discuss v-on:setTotalDiscussion="setTotalDiscussion($event)" :product_id="product_detail.id"></discuss>
              </b-tab>
            </b-tabs>
          </b-row>
        </b-col>
        <b-col cols="3" v-if="product_detail.store">
          <b-row class="card ml-3 pt-2">
            <b-row class="pl-2">
              <div class="col-4 pr-0">
              <img class="foto-square rounded-circle" :src="product_detail.store.logo" alt="" style="height:80px; width:80px">
              </div>
              <b-col class="col-8">
                <router-link :to="'/'+product_detail.store.store_url">
                  <span>{{product_detail.store.name}}</span>
                </router-link>
                <p class="text-muted">{{activity}}</p>
              </b-col>
            </b-row>
          <b-col>
            <hr>
            <b-row class="text-12 pl-3 pt-4">
              <b-col cols="3" class="text-muted  p-0">
                <span>Pelanggan</span>
              </b-col>
              <b-col cols="9">
                <span>{{product_detail.follower_count}}</span>
              </b-col>
              <b-col cols="3" class="text-muted  p-0">
                <span>Transaksi</span>
              </b-col>
              <b-col cols="9" class="pr-0">
                <span>{{store_transaction_statistic}}</span>
              </b-col>
              <b-col cols="3" class="text-muted  p-0">
                <span>Penilaian</span>
              </b-col>
              <b-col cols="9" class="pr-0">
                <span>{{store_review_statistic}}</span>
              </b-col>
              <b-col cols="3" class="text-muted  p-0">
                <span>Bergabung</span>
              </b-col>
              <b-col cols="9">
                <span>{{product_detail.store.created_at | moment("Do MMMM YYYY")}}</span>
              </b-col>
            </b-row>
            <hr>
            <div class=" pt-4">
              <div>
                <span class="text-muted">Dukungan Pengiriman :</span>
              </div>
              <div class="text-sedang" v-for="data in product_detail.store.store_logistic" :key="data.id">
                <span><img :src="data.logistic.logo" alt="" class="courier-logo ml-0"></span>
                <span>{{data.logistic.name}}</span>
              </div>
            </div>
          </b-col>
          </b-row>
        </b-col>
        </template>
        <template v-else>
          <b-card>
            <span>Produk Ini Belum Memiliki Review</span>
          </b-card>
        </template>
      </b-row>
      <newChat v-if="starNewChat.active" :starNewChat="starNewChat" v-on:updateStarNewChat="updateStarNewChat"></newChat>
    </div>
  </div>
</template>

<script>
import newChat from '../../message/newMessage'
import LightBox from 'vue-image-lightbox'
import discuss from './ProductDiscussion'
import specification from './ProductSpecification'
import information from './ProductInformation'
import review from './ProductReview'
import toastr from 'toastr'
import helper from '../../../services/helper'
export default {
  components: {
    LightBox,
    discuss,
    specification,
    information,
    review,
    toastr,
    newChat
  },
  data () {
    return {
      total_discussion : 0,
      main_image : '0',
      product_detail : {},
      product_id : this.$route.params.product_id,
      store_url : this.$route.params.store_url,
      product : {
        quantity : 0,
        product_id : this.$route.params.product_id,
      },
      addToCartButton : true,
      starNewChat : {
        active : false,
        receiver_type : 'store',
        receiver_id : '',
        receiver_name : ''
      }
    }
  },
  watch: {
    '$route' (to, from) {
      this.product_id = this.$route.params.product_id;
      this.store_url = this.$route.params.store_url;
      this.getProductDetail();
    },
    'product_detail.stock' () {
      if(this.product_detail.stock > 0) {
        this.addToCartButton = false
      }
    }
  },
  mounted() {
    window.scrollTo(0,0);
    this.getProductDetail();
    this.total_star = _.cloneDeep(this.product_detail);
    this.notification();
  },
  computed: {
    star_total: function () {
      if(this.product_detail.product_statistic){
        return this.product_detail.product_statistic.review_count
      }else{
        return 0
      }
    },
    price: function () {
      if(this.product_detail.discount > 0){
        return this.product_detail.price * (100 - this.product_detail.discount) / 100;
      }
    },
    review_total: function () {
      var data;
      if(this.product_detail.product_statistic){
        var data = this.product_detail.product_statistic.review_count
      }
      if(data){
        return data
      }else{
        return 0
      }
    },
    review_avg: function () {
      var data = this.product_detail.product_statistic.review_avg
      if(data){
        return data
      }else{
        return 0
      }
    },
    activity: function(){
        var a = this.product_detail.store.user.last_activity;
        var b = this.$moment.duration(this.$moment().diff(a));
        var range = Math.ceil(b.asHours());
        console.log(range);
        var result = ''
        if(range > 8640){
          result = 'Tidak aktif lebih dari setahun'
        }
        else if(range > 720){
          result = 'Aktif '+Math.ceil(range/720)+' bulan lalu'
        }
        else if(range > 24){
          result = 'Aktif '+Math.round(range/24)+' hari lalu'
        }else if(range > 1 && range < 24){
          result = 'Aktif '+range+' jam lalu'
        }else{
          result = 'Sedang aktif'
        }
        return result;
    },
    store_review_statistic: function () {
      var data = this.product_detail.store.store_statistic
      if(data.review_count > 0){
        return data.review_avg+' bintang dari '+data.review_count+ ' penilaian'
      }else{
        return 'belum ada penilaian'
      }
    },
    store_transaction_statistic: function () {
      var data = this.product_detail.store.store_statistic
      if(data.order > 0){
        return 'Menerima '+data.order_accept+' dari '+data.order+ ' ('+Math.ceil(data.order_accept/data.order*100)+'%)'
      }else{
        return 'belum pernah transaksi'
      }
    },
  },
  methods: {
    AddToCart(){
      axios.post('/api/user/carts',this.product)
      .then(res => {
        toastr['success']("Berhasil ditambahkan ke keranjang");
        Vue.bus.$emit('update-notifications');
      })
      .catch(error => {
        if (error.response.status == 401) {
          toastr['error']('Anda Belum Masuk !');
          this.$router.push({ name: 'Login', query: { redirect: this.$route.path } });
        }
        if (error.response.status == 406) {
          toastr['error'](error.response.data.message);
        }
      });
    },
    addToWishlist(){
      axios.post('/api/user/wishlists',{product_id : this.product_id})
      .then(res => {
        this.product_detail.wishlist = true;
        toastr['success']('Produk berhasil ditambahkan ke wishlist');
      })
      .catch(error => {if (error.response.status == 401) {
          toastr['error']('Anda Belum Masuk !');
          this.$router.push({ name: 'Login', query: { redirect: this.$route.path } });
        }
      });
    },
    removeWishlist(){
      axios.delete('/api/user/wishlists/'+this.product_id)
      .then(res => {
        this.product_detail.wishlist = false;
        toastr['success']('Produk berhasil dihapus dari wishlist');
      })
      .catch(error => {if (error.response.status == 401) {
          toastr['error']('Anda Belum Masuk !');
          this.$router.push({ name: 'Login', query: { redirect: this.$route.path } });
        }
      });
    },
    setTotalDiscussion(total){
      this.total_discussion = total;
    },
    updateStarNewChat(){
      this.starNewChat.active = false;
    },
    toInteger: function (value) {
      return parseFloat(value, 1);
    },
    selectImage(index) {
      this.main_image = index
    },
    openLightBox(){
      this.$refs.lightbox.showImage(this.main_image)
    },
    getProductDetail(){
      axios.get('/api/user/products/'+this.product_id)
      .then(res => {
        this.product_detail = res.data;
      })
      .catch(err => {
        console.error(err); 
      })
    },
    sendNewChat(id){
      this.starNewChat.active = true;
      this.starNewChat.receiver_id = id;
      this.starNewChat.receiver_name = this.product_detail.store.name;
    },
    notification(){
          toastr.options = {
              "positionClass": "toast-top-right"
          };

          $('[data-toastr]').on('click',function(){
              var type = $(this).data('toastr'),message = $(this).data('message'),title = $(this).data('title');
              toastr[type](message, title);
          });
      },
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
    slug(value){
      return helper.toSlug(value);
    }
  },
}
</script>