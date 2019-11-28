<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <label class="lead text-tebal">Jasa Kirim Toko</label>
      <div role="tablist">
        <template v-for="data in logistic" >
            <b-card no-body class="mb-1 setting-logistic" :key="data.id">
              <b-card-header header-tag="header" class="p-1" role="tab">
                <span class="text-sedang text-tebal logistic my-auto mouse-pointer" v-b-toggle="'service'+data.id">{{data.name}}</span>
                <span class="float-right">
                    <a v-if="data.is_used" v-on:click="updateLogistic(data.id)"><i class="mx-1 fa fa-toggle-on fa-2x mouse-pointer"></i></a>
                    <a v-else v-on:click="updateLogistic(data.id)"><i class="mx-1 fa fa-toggle-off fa-2x mouse-pointer"></i></a>
                    <i v-b-toggle="'service'+data.id" class="fa fa-angle-double-down fa-lg"></i>
                </span>
              </b-card-header>
              <b-collapse :id="'service'+data.id" accordion="my-accordion" role="tabpanel">
              <b-card-body>
                  <p class="card-text">
                  {{data.about}}
                  </p>
                  <p class="card-text">
                  </p>
              </b-card-body>
              </b-collapse>
            </b-card>
        </template>
      </div>
      </b-card>
    </div>
  </div>
</template>

<script>
import { Switch as cSwitch } from '@coreui/vue'
export default {
  name: 'Logistic',
  components: {
  },
  data () {
    return {
      logistic : {}
    }
  },
  mounted() {
    this.getLogistic();
  },
  methods: {
    getLogistic() {
      axios.get('/api/user/seller/stores?type=logistic')
      .then(res => {
        this.logistic = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
    updateLogistic(id) {
      axios.patch('/api/user/seller/stores/'+id, {type : 'update_logistic'})
      .then(res => {
        this.getLogistic();
      })
      .catch(err => {
        console.error(err); 
      })
      
    }
  }
}
</script>
