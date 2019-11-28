<template>
  <div class="animated fadeIn" v-show="starNewChat.active == true">
    <div class="container">
        <div class="row pt-3">
            <div class="chat-main">
                <div class="col-md-12 chat-header">
                    <div class="row header-one text-white p-1">
                        <div class="col-md-10 name pl-2">
                            <i class="fa fa-comment"></i>
                            <h6 class="ml-1 mb-0">{{starNewChat.receiver_name}}</h6>
                        </div>
                        <div class="col-md-2 options text-right pr-0">
                            <i class="fa fa-times hover text-center pt-1" v-on:click="updateStarNewChat()"></i>
                        </div>
                    </div>
                </div>
                <div class="chat-content">
                    <div class="col-md-12 chats pl-2 pr-3 pb-3 pt-2">
                        <ul class="p-0">
                            <template v-for="(item, index) in messages.message_detail">
                            <li v-if="item.recipient_id == user_id" class="receive-msg float-left mb-2" :key="index">
                                <div class="sender-img">
                                    <img :src="messages.store.logo" class="float-left">
                                </div>
                                <div class="receive-msg-desc float-left ml-2">
                                    <p class="bg-white m-0 pt-1 pb-1 pl-2 pr-2 rounded">
                                        {{item.message}}
                                    </p>
                                    <span class="receive-msg-time">{{item.created_at | moment("HH:mm, Do MMM YYYY")}}</span>
                                </div>
                            </li>
                            <li v-else class="send-msg float-right mb-2" :key="index">
                                <p class="pt-1 pb-1 pl-2 pr-2 m-0 rounded">
                                    {{item.message}}
                                </p>
                                <span class="send-msg-time">{{item.created_at | moment("HH:mm, Do MMM YYYY")}}</span>
                            </li>
                            </template>
                        </ul>
                    </div>
                    <div class="col-md-12 p-2 msg-box border border-primary">
                        <div class="row pl-3">
                            <div class="col-sm-10 pl-0">
                                <input v-model="textMessage" type="text" class="form-control" placeholder=" Send message" />
                            </div>
                            <div class="col-sm-2 text-center options-right pl-0 pr-0">
                                <i v-if="textMessage" v-on:click="sendMessage()" class="fa fa-paper-plane-o mr-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import helper from '../../services/helper'
export default {
  name: 'newMessage',
  props : [
      'starNewChat',
  ],
  components: {
  },
  data: function () {
    return {
        messages : {},
        user_id : this.$store.getters.getAuthUser('id'),
        textMessage : '',
    }
  },
  mounted() {
    this.getMessage();
    if(this.starNewChat.active == true){
        window.setInterval(() => {
            this.getMessage();
        },10000);
    }
  },
  watch: {
      'starNewChat' : function(){
          this.getMessage();
      }
  },
  methods: {
    updateStarNewChat(){
        this.$emit('updateStarNewChat');
    },
    getMessage(){
        var type = ''
        if(this.starNewChat.receiver_type == 'store'){
            type = 'store_id'
        }else if(this.starNewChat.receiver_type == 'user'){
            type = 'user_id'
        }
        axios.get('/api/user/messages?type=search&'+type+'='+this.starNewChat.receiver_id)
        .then(res => {
            this.messages = res.data;
        })
        .catch(error => {
            if (error.response.status == 401) {
            toastr['error']('Anda Belum Masuk !');
            this.starNewChat.active = false
            this.$router.push({ name: 'Login', query: { redirect: this.$route.path } });
            }
            if (error.response.status == 406) {
            toastr['error'](error.response.data.message);
            }
        })
    },
    sendMessage(){
        if(this.messages.id){
            this.replyMessage();
            console.log(true)
        }else{
            this.newMessage();
            console.log(false)
        }
    },
    newMessage(){
        axios.post('/api/user/messages', {store_id: this.starNewChat.receiver_id, message: this.textMessage})
        .then(res => {
        if(res){
          this.getMessage();
          this.textMessage = ''
          toastr.success('Berhasil Mengirim Pesan')
        }
        })
        .catch(err => {
            console.error(err); 
        })
    },
    replyMessage(){
        axios.patch('/api/user/messages/'+this.messages.id, {type : 'reply', message: this.textMessage})
        .then(res => {
        if(res){
          this.getMessage();
          this.textMessage = ''
          toastr.success('Berhasil Mengirim Pesan')
        }
      })
      .catch(err => {
        console.error(err); 
      })
    },
  }
}
</script>