<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm=2>
            <Sidebar chartId="card-chart-01" class="storeproduct-sidebar"/>
      </b-col>
      <b-col sm=10>
            <router-view></router-view>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Sidebar from './Sidebar'

export default {
  name: 'index',
  components: {
    Sidebar
  },
  data: function () {
    return {
    }
  },
  methods: {
    
  }
}
</script>
