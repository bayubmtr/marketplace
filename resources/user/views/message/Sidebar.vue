<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card class="message-menu" no-body header="Pesan" header-class="text-besar text-tebal">
        <b-card no-body>
          <b-tabs card>
            <b-tab  class="scroll" title="Semua Pesan" active>
                <b-row class="menu-list border-top border-bottom" v-for="data in Messages" :key="data.id" v-on:click="getMessages(data[0].conversation_id)" >
                  <b-col cols="1" class="my-auto text-center m-0 p-0">
                    <span><i class="fa fa-user-o"></i></span>
                  </b-col>
                  <b-col cols="11" class="pl-0">
                    <b-col class="pl-0">
                      <span class="menu-user">{{ data[0].sender_id }}</span><small class="float-right">05/01/19</small>
                    </b-col>
                    <b-col class="pl-0">
                      <span class="menu-text">{{ data[0].message }}</span>
                    </b-col>
                  </b-col>
                </b-row>
            </b-tab>
            <b-tab title="Belum Dibaca">
              Belum Dibaca
            </b-tab>
          </b-tabs>
        </b-card>
        <div class="dropdown-divider"></div>
      </b-card>
    </div>
  </div>
</template>

<script>
import helper from '../../services/helper'
import pagination from 'laravel-vue-pagination'
import Message from './MessageBody'
export default {
  name: 'Product',
  data: function () {
    return {
      Messages : {},
      Conversations : {},
      show: true,
      appConversation : Message,
    }
  },
  mounted() {
    this.getAllMessages();
  },
  methods: {
    getAllMessages() {
      axios.get('/api/user/message')
          .then(response => this.Messages = response.data );
    },
    getMessages(id) {
      axios.get('/api/user/message/'+id)
          .then(response => this.Conversations = response.data );
    },
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  }
}
</script>
