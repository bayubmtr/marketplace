<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Pesan</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar"  placeholder="Search" >
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div>
            </div>
          </div>
          <div class="inbox_chat">
            <template  v-for="(item, index) in Messages">
            <div :class="'chat_list mouse-pointer '+new_chat_css(item.message_detail)+' '+active_chat(item.id)" v-on:click="messageClick(item.id, index)" :key="index">
              <div class="chat_people">
                <div class="chat_img"> <img :src="item.avatar" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>{{item.user}}<span class="chat_date">{{item.created_at | moment("Do MMM YYYY")}}</span></h5>
                  <span>{{new_chat(item.message_detail)}}</span>
                </div>
              </div>
            </div>
            </template>
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history" id="msg-scroll">
            <template v-for="(item, index) in Conversations">
            <div class="incoming_msg" v-if="item.recipient_id == user_id || item.recipient_id == store_id" :key="index">
              <div class="incoming_msg_img"> <img :src="Messages[message_index].avatar" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>{{item.message}}</p>
                  <span class="time_date"> {{item.created_at | moment("h:mm")}}    |    {{item.created_at | moment("dddd, Do MMM YYYY")}}</span></div>
              </div>
            </div>
            <div class="outgoing_msg" :key="index" v-else>
              <div class="sent_msg">
                <p>{{item.message}}</p>
                <span class="time_date"> {{item.created_at | moment("h:mm")}}    |    {{item.created_at | moment("dddd, Do MMM YYYY")}}</span>
                <div v-if="index == Conversations.length - 1"><small v-if="item.read == 0">Belum Dibaca</small></div>
              </div>
            </div>
            </template>
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input :disabled="message_id ? false:true" type="text" v-model="text_message" class="write_msg" placeholder="Tulis Pesan Baru" />
              <button :disabled="text_message ? false:true" v-on:click="sendMessages()" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
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
  name: 'Message',
  components: {
  },
  data () {
    return {
      Messages : {},
      Conversations : {},
      Users : {},
      Name : '',
      message_id : 0,
      message_index : 0,
      recipient_id : '',
      text_message : '',
      store_id : this.$store.getters.getAuthUser('store_id'),
      new_to_user : '',
      new_to_store : '',
      new_to_name : '',
      user_id : this.$store.getters.getAuthUser('id'),
    }
  },
  mounted() {
    this.getAllMessages();
    this.getUser();
    if(this.$route.query.to_user){
      this.new_to_user = this.$route.query.to_user
      this.new_to_name = this.$route.query.to_name
      this.conversation_id = null
      this.recipient_id = this.new_to_user
    }
    if(this.$route.query.to_store){
      this.new_to_store = this.$route.query.to_store
      this.new_to_name = this.$route.query.to_name
      this.conversation_id = null
      this.recipient_id = this.new_to_store
    }
    window.setInterval(() => {
      this.getAllMessages();
    },10000);
  },
  methods: {
    getUser() {
      axios.get('/api/user/auth/user')
          .then(response => this.Users = response.data );
    },
    getAllMessages() {
      axios.get('/api/user/messages')
          .then(response => this.Messages = response.data)
      console.log(this.message_id)
    },
    messageClick(id,index){
      this.getMessages(id);
      this.message_index = index;
    },
    getMessages(id) {
      this.message_id = id;
      axios.get('/api/user/messages/'+id)
      .then(response => {
          this.Conversations = response.data;
      })
      .catch(err => {
          this.Conversations = null
      })
    },
    sendMessages() {
      axios.patch('/api/user/messages/'+this.message_id, {type : 'reply', message: this.text_message})
      .then(res => {
        if(res){
          this.getAllMessages();
          this.getMessages(this.message_id);
          this.text_message = ''
          toastr.success('Berhasil Mengirim Pesan')
          this.$router.replace({'query': null});
        }
      })
      .catch(err => {
        console.error(err); 
      })
    },
    scrollToEnd: function() {    	
      var container = this.$el.querySelector("#msg-scroll");
      container.scrollTop = container.scrollHeight;
    },
    new_chat_css(data){
      if(data[data.length-1].recipient_id == this.user_id || data[data.length-1].recipient_id == this.store_id){
        if(data[data.length-1].is_read == 0){
          return 'new_chat'
        }
      }
    },
    new_chat(data){
      return data[data.length-1].message
    },
    active_chat(data){
      if(data == this.message_id){
        return 'active_chat'
      }
    },
    newConversation(){

    },
    checkNewMessage(value){
      for(var x in value){
        if(x+1 == value.length){
          if(value[x].is_read == 0 && this.$store.getters.getAuthUser('id') != sender){
            return 'new_chat'
          }
        }
      }
    },
    getStoreId(){
      return this.$store.getters.getStoreId();
    }
  },
  watch: {
    Messages(){
      if(this.message_id == ''){
        this.getMessages(this.Messages[0].id)
      }else{
        this.getMessages(this.message_id)
      }
    },
    Conversations(){
      this.scrollToEnd()
    },
    new_to_store(){
      this.getMessages('new');
      console.log('xxx')
    },
    new_to_user(){
      this.getMessages('new');
      console.log('xxx')
    }
  },
}
</script>