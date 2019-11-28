<template>
  <div class="animated fadeIn">
    <b-col>
      <b-card>
          <div slot="header">
              <span class="lead">Toko Langganan</span>
          </div>
          <b-card v-for="(item, index) in followings" :key="index">
            <b-row>
              <b-col sm=2>
                <img :src="item.store.logo" alt=""  class="store-profile-avatar mx-auto d-block">
              </b-col>
              <b-col sm=10>
                <b-row>
                  <b-col sm=8>
                    <router-link :to="'/'+item.store.store_url"><h3>{{item.store.name}}</h3></router-link>
                    <span>{{item.store.slogan}}</span>
                    <div>
                      <span v-html="activity(item.store.user.last_activity)">{{}}</span>
                    </div>
                  </b-col>
                  <b-col sm=4>
                    <div class="float-right" v-if="item.store.store_url">
                      <span>Bagikan :</span>
                      <facebook :url="'https://byxmart.store/'+item.store.store_url" scale="2"></facebook>
                      <google :url="'https://byxmart.store/'+item.store.store_url" scale="2"></google>
                      <twitter :url="'https://byxmart.store/'+item.store.store_url" scale="2"></twitter>
                    </div>
                  </b-col>
                </b-row>
                <hr>
                <b-row>
                  <b-col sm=8>
                    <b-row v-if="item.store.store_statistic">
                      <b-col sm=3>
                        <span><i class="fa fa-database"></i> Produk   : <span style="color:red">{{item.store.store_statistic.product_count}}</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-exchange"></i> Total Pesanan   : <span style="color:red">{{item.store.store_statistic.order}}</span></span>
                      </b-col>
                      <b-col sm=5 class="m-0 p-0">
                        <span><i class="fa fa-check"></i> Pesanan Diterima   : <span style="color:red">{{item.store.store_statistic.order_accept}} ({{orderPercentage(item.store.store_statistic.order, item.store.store_statistic.order_accept)}}%)</span></span>
                      </b-col>
                      <b-col sm=3>
                        <span><i class="fa fa-users"></i> Pengikut   : <span style="color:red">{{item.store.store_statistic.follower_count}}</span></span>
                      </b-col>
                      <b-col sm=4>
                        <span><i class="fa fa-star"></i> Penilaian   : <span style="color:red">{{item.store.store_statistic.review_avg}} ({{item.store.store_statistic.review_count}})</span></span>
                      </b-col>
                      <b-col sm=5 class="m-0 p-0">
                        <span><i class="fa fa-check-square-o"></i> Bergabung   : <span style="color:red">{{item.store.created_at | moment('DD MMM YYYY')}}</span></span>
                      </b-col>
                    </b-row>
                  </b-col>
                  <b-col sm=4>
                    <div class="float-right">
                      <button v-on:click="followStore('unfollow', item.store.id)" type="button" class="btn btn-outline-dark"><i class="fa fa-times"></i> Unfollow</button>
                      <button v-on:click="sendNewChat(item.store.id, item.store.name)" type="button" class="btn btn-outline-dark"><i class="fa fa-wechat"></i> Chat</button>
                    </div>
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
          </b-card>
      </b-card>
    </b-col>
    <newChat style="z-index: 99999; position : relative" v-if="starNewChat.active" :starNewChat="starNewChat" v-on:updateStarNewChat="updateStarNewChat"></newChat>
  </div>
</template>

<script>
import newChat from '../message/newMessage'
import { Facebook, Google, Twitter } from 'vue-socialmedia-share';
export default {
  name: 'index',
  components: {
    newChat,
    Facebook,
    Twitter,
    Google
  },
  data: function () {
    return {
      followings : {},
      starNewChat : {
        active : false,
        receiver_type : 'store',
        receiver_id : '',
        receiver_name : ''
      },
    }
  },
  mounted() {
    this.getFollowings();
  },
  methods: {
    getFollowings(){
      axios.get('/api/user/follows')
      .then(res => {
        this.followings = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
    orderPercentage(order,accept){
        if(accept == 0 && order == 0){
          return 100;
        }else if(accept == 0 || order == 0){
          return 0;
        }
        return Math.ceil(accept/order*100);
    },
    activity: function(last_activity){
        var a = last_activity;
        var b = this.$moment.duration(this.$moment().diff(a));
        var range = Math.ceil(b.asHours());
        var result = ''
        if(range > 8640){
          result = '<i class="fa fa-circle text-danger"></i> Tidak aktif lebih dari setahun'
        }
        else if(range > 720){
          result = '<i class="fa fa-circle text-danger"></i> Aktif '+Math.ceil(range/720)+' bulan lalu'
        }
        else if(range > 24){
          result = '<i class="fa fa-circle text-warning"></i> Aktif '+Math.round(range/24)+' hari lalu'
        }else if(range > 1 && range < 24){
          result = '<i class="fa fa-circle text-warning"></i> Aktif '+range+' jam lalu'
        }else{
          result = '<i class="fa fa-circle text-success"></i> Sedang aktif'
        }
        return result;
    },
    followStore(type, store_id){
      axios.post('/api/user/follows', {store_id : store_id})
      .then(res => {
        toastr.success('Berhasil '+type+' toko')
        this.getFollowings();
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
    },
    sendNewChat(id, name){
      this.starNewChat.active = true;
      this.starNewChat.receiver_id = id;
      this.starNewChat.receiver_name = name;
    },
    updateStarNewChat(){
      this.starNewChat.active = false;
    },
  }
}
</script>
