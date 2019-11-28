<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-row>
        <b-col>
          <div class="card mb-3">
            <img :src="user.background" class="card-img-top">
            <div class="card-body">
            <b-row>
              <b-col sm=2>
                <img :src="user.logo" alt=""  class="store-profile-avatar mx-auto d-block">
              </b-col>
              <b-col sm=10>
                <b-row>
                  <b-col sm=8>
                    <h3>{{user.name}}</h3>
                    <span>{{user.slogan}}</span>
                  </b-col>
                  <b-col sm=4>
                    <div class="float-right" v-if="user.store_url">
                      <span>Bagikan :</span>
                      <facebook :url="'https://byxmart.store/'+user.store_url" scale="2"></facebook>
                      <google :url="'https://byxmart.store/'+user.store_url" scale="2"></google>
                      <twitter :url="'https://byxmart.store/'+user.store_url" scale="2"></twitter>
                    </div>
                  </b-col>
                </b-row>
                <hr>
                <b-row>
                  <b-col sm=8>
                    <b-row v-if="user.store_statistic">
                      <b-col sm=4>
                        <span><i class="fa fa-database"></i> Produk   : <span style="color:red">{{user.store_statistic.product_count}}</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-user-plus"></i> Penjualan   : <span style="color:red">{{user.store_statistic.sold_count}}</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-users"></i> Pengikut   : <span style="color:red">{{user.follower_count}}</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-star"></i> Penilaian   : <span style="color:red">{{user.store_statistic.review_avg}} ({{user.store_statistic.review_count}})</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-check-square-o"></i> Bergabung   : <span style="color:red">{{user.created_at | formatDate}}</span></span>
                      </b-col>
                    </b-row>
                  </b-col>
                  <b-col sm=4>
                    <div class="float-right">
                      <button v-on:click="followStore('follow')" v-if="user.following == false" type="button" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Follow</button>
                      <button v-on:click="followStore('unfollow')" v-if="user.following == true" type="button" class="btn btn-outline-dark"><i class="fa fa-times"></i> Unfollow</button>
                      <button v-on:click="sendNewChat()" type="button" class="btn btn-outline-dark"><i class="fa fa-wechat"></i> Chat</button>
                    </div>
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            </div>
          </div>
        </b-col>
      </b-row>
    </div>
    <newChat style="z-index: 99999; position : relative" v-if="starNewChat.active" :starNewChat="starNewChat" v-on:updateStarNewChat="updateStarNewChat"></newChat>
  </div>
</template>

<script>
import { Facebook, Google, Twitter } from 'vue-socialmedia-share';
import newChat from '../../message/newMessage'
import helper from '../../../services/helper'
export default {
  props: [
    'user'
  ],
  components: {
    newChat,
    Facebook,
    Twitter,
    Google
  },
  name: 'Promo',
  data () {
    return {
      starNewChat : {
        active : false,
        receiver_type : 'store',
        receiver_id : '',
        receiver_name : ''
      },
    }
  },
  methods: {
    updateStarNewChat(){
      this.starNewChat.active = false;
    },
    sendNewChat(id){
      this.starNewChat.active = true;
      this.starNewChat.receiver_id = this.user.id;
      this.starNewChat.receiver_name = this.user.name;
    },
    followStore(type){
      axios.post('/api/user/follows', {store_id : this.user.id})
      .then(res => {
         toastr.success('Berhasil '+type+' toko')
         this.user.following = res.data.following
      })
      .catch(error => {
        if (error.response.status == 401) {
          toastr['error']('Anda Belum Masuk !');
          this.$router.push({ name: 'Login', query: { redirect: this.$route.path } });
        }
        else {
          toastr['error'](error.response.data.message);
        }
      })
    }
  },
  filters: {
    formatDate(date) {
        return helper.formatDate(date);
    },
  },
}
</script>
