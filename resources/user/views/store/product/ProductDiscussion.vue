<template>
    <div class="wrapper">
        <div class="animated fadeIn p-2">
            <div class="input-group pt-1">
                <b-form-input type="text" :placeholder="inputDiscussion.placeholder" :state="inputDiscussion.state" class="form-control mr-2" v-model="discussionForm.discussion"></b-form-input>
                <button v-on:click="newDiscussion" type="button" class="btn btn-outline-primary mr-2">Tanya Penjual</button>
            </div>
            <b-card body-class="p-0" class="mt-3" v-for="(value, index) in discussion.data" :key="value.id">
                <b-card-header header-class="pt-0 pb-0">
                    <b-row>
                    <b-col cols="1">
                        <img class="foto-square rounded-circle mt-2" :src="value.user.avatar" alt="" style="height:40px">
                    </b-col>
                    <b-col cols="11">
                        <span class="text-tebal">{{value.user.first_name}} {{value.user.last_name}} - </span><span class="text-kecil text-muted">{{value.created_at}}</span>
                        <span v-on:click="destroyDiscussion(value.id)" v-if="authUserId == value.user_id" class="fa fa-trash mouse-pointer float-right pt-2"></span>
                        <p class="text-sedang">
                            {{value.discussion}}
                        </p>
                    </b-col>
                    </b-row>
                </b-card-header>
                <b-card-body class="pl-5 pt-2">
                    <div v-if="showAllDiscussionReplay == false">
                        <span v-if="value.reply.length > 2" class="show-more mouse-pointer" v-on:click="showAllDiscussionReplay = true"><i class="fa fa-comments-o fa-lg"></i> Lihat {{ value.reply.length - 2 }} jawaban lainnya</span>
                        <b-row class="pt-2" v-for="(item, index) in value.reply.slice(0, 2)" :key="item.id">
                            <b-col cols="1">
                                <img class="foto-square rounded-circle" :src="item.user.avatar" alt="" style="height:40px">
                            </b-col>
                            <b-col cols="11">
                                <span class="text-tebal">{{item.user.first_name}} {{item.user.last_name}} - </span><span class="text-kecil text-muted">28 Feb 16:35</span>
                                <span v-on:click="destroyDiscussionReply(item.id)" v-if="authUserId == item.user_id" class="fa fa-trash mouse-pointer float-right"></span>
                                <p class="text-sedang">
                                {{item.discussion}}
                                </p>
                            </b-col>
                            <b-col class="p-0 m-0" cols="12" v-if="index+1 < 2">
                                <hr>
                            </b-col>
                        </b-row>
                    </div>
                    <div v-if="showAllDiscussionReplay == true">
                        <b-row v-for="(item, index) in value.reply" :key="item.id">
                            <b-col cols="1">
                                <img class="foto-square rounded-circle" :src="item.user.avatar" alt="" style="height:40px">
                            </b-col>
                            <b-col cols="11">
                                <span class="text-tebal">{{item.user.first_name}} {{item.user.last_name}} - </span><span class="text-kecil text-muted">28 Feb 16:35</span>
                                <span v-on:click="destroyDiscussionReply(item.id)" v-if="authUserId == item.user_id" class="fa fa-trash mouse-pointer float-right"></span>
                                <p class="text-sedang">
                                {{item.discussion}}
                                </p>
                            </b-col>
                            <b-col class="p-0" cols="12" v-if="index+1 < value.reply.length">
                                <hr>
                            </b-col>
                        </b-row>
                    </div>
                    <div>
                    <textarea v-model="ReplyDiscussion[value.id]" v-on:click="replyDiscussionClick('y', index)" placeholder="Balas Diskusi" type="text" class="form-control mr-2" :rows="RowInputDiscussion[index]"></textarea>
                    <div class="text-right mt-2"  v-if="isReplyDiscussion[index] == 'y'">
                        <button v-on:click="replyDiscussionClick('n', index)" type="button" class="btn btn-outline-primary mr-2">Batal</button>
                        <button v-on:click="newReplyDiscussion(value.id)" type="button" class="btn btn-outline-primary mr-2">Kirim</button>
                    </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>
    </div>
</template>
<script>

import toastr from 'toastr'
export default {
    props: [
    'product_id'
  ],
  components: {
    toastr
  },
  data: function () {
    return {
        discussion : {},
        showAllDiscussion : false,
        showAllDiscussionReplay : false,
        ReplyDiscussion : [],
        RowInputDiscussion : [],
        isReplyDiscussion : [],
        inputDiscussion : {   
            state : null,
            placeholder : 'Apa yang ingin anda tanya kan ?'
        },
        discussionForm : {
            product_id : '',
            type : 'discussion',
            discussion : '',
        },
        replyDiscussionForm : {
            discussion_id : '',
            product_id : this.$route.params.product_id,
            type : 'discussion_reply',
            discussion_reply : '',
        }
    }
  },
  mounted() {
      this.getDiscussion();
  },
  computed: {
      authUserId(){
            return this.$store.getters.getAuthUser('id');
      },
  },
  watch: {
      product_id: function (){
          this.getDiscussion()
      }
  },
  methods: {
      getDiscussion(){
        if(this.product_id){
            axios.get('/api/user/discussions?product_id='+this.product_id)
            .then(res => {
                this.discussion = res.data
                this.$emit('setTotalDiscussion', this.discussion.data.length);

            })
            .catch(err => {
                console.error(err); 
            })
        }
      },
      replyDiscussionClick(value, index){
          if(value == 'y'){
            this.isReplyDiscussion[index] =  'y';
            Vue.set(this.RowInputDiscussion, index, "4");
          }
          if(value == 'n'){
            this.isReplyDiscussion[index] = 'n';
            Vue.set(this.RowInputDiscussion, index, "2");
          }
      },
      newDiscussion(){
        if(this.discussionForm.discussion){
            this.discussionForm.product_id = this.product_id;
            axios.post('/api/user/discussions',this.discussionForm)
            .then(res => {
                if(res.data.error == "Unauthorized"){
                    toastr['error']('Anda Belum Masuk !')
                }else if(res.status == 200){
                    toastr['success']('Diskusi berhasil dikirim !')
                    this.getDiscussion()
                    this.discussionForm.discussion = ''
                }else{
                    toastr['error']('Sistem sedang bermasalah !')
                }
            })
            .catch(function (error) {
                if (error.response.status == 401) {
                    toastr['error']('Anda Belum Masuk !')
                }
            });
        }else{
            this.inputDiscussion.state = false
            this.inputDiscussion.placeholder = "Form ini tidak boleh kosong !"
        }
      },
      newReplyDiscussion(discussion_id){
          this.replyDiscussionForm.discussion_id = discussion_id;
        if(this.ReplyDiscussion[discussion_id]){
            this.replyDiscussionForm.discussion_reply = this.ReplyDiscussion[discussion_id]
            axios.post('/api/user/discussions',this.replyDiscussionForm)
            .then(res => {
                if(res.data.error == "Unauthorized"){
                    toastr['error']('Anda Belum Masuk !')
                }else if(res.status == 200){
                    toastr['success']('Balasan diskusi berhasil dikirim !')
                    this.getDiscussion()
                    this.ReplyDiscussion[discussion_id] = ''
                }else{
                    toastr['error']('Sistem sedang bermasalah !')
                }
            })
            .catch(err => {
                toastr['error'](err);
            })
        }else{
            this.inputDiscussion.state = false
            this.inputDiscussion.placeholder = "Form ini tidak boleh kosong !"
        }
      },
      destroyDiscussion(discussion_id){
          let options = {
        okText: 'Hapus',
        cancelText: 'Batal',
        }
      this.$dialog
      .confirm('Hapus Diskusi ini ?', options)
      .then(xxx => {
        axios.delete('/api/user/discussions/'+discussion_id+'?type=discussion')
          .then(res => {
              if(res.data.error == "Unauthorized"){
                    toastr['error']('Anda Belum Masuk !')
                }else if(res.status == 200){
                    toastr['success']('Diskusi berhasil dihapus !')
                    this.getDiscussion()
                }else{
                    toastr['error']('Sistem sedang bermasalah !')
                }
          })
          .catch(err => {
              toastr['error'](err);
          })
      })
      .catch(function() {
        toastr['error'](err);
      });
          
      },
      destroyDiscussionReply(discussion_reply_id){
          axios.delete('/api/user/discussions/'+discussion_reply_id+'?type=discussion_reply')
          .then(res => {
              if(res.data.error == "Unauthorized"){
                    toastr['error']('Anda Belum Masuk !')
                }else if(res.data.status == 201){
                    toastr['success']('Diskusi berhasil dihapus !')
                    this.getDiscussion()
                }else{
                    toastr['error']('Sistem sedang bermasalah !')
                }
          })
          .catch(err => {
              toastr['error'](err); 
          })
      },
      loadMoreDiscussion(){
          axios
      }
  },
}
</script>