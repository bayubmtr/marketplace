<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm=4>
            <Sidebar chartId="card-chart-01" class="storeproduct-sidebar"/>
      </b-col>
      <b-col sm=8>
            <MessageBody chartId="card-chart-01" class="message-body"/>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Sidebar from './Sidebar'
import MessageBody from './MessageBody'

export default {
  name: 'index',
  components: {
    Sidebar,
    MessageBody
  },
  data: function () {
    return {
    }
  },
  methods: {
    
  }
}
</script>
