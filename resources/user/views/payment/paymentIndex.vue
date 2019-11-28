<template>
  <div class="animated fadeIn">
    <b-row class="justify-content-center">
      <b-col cols="7">
        <router-view>
        </router-view>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import paymentStatus from './paymentStatus'
import paymentUnfinish from './paymentUnfinish'
import paymentError from './paymentError'
export default {
  name: 'index',
  components: {
    paymentStatus,
    paymentUnfinish,
    paymentError
  },
  data: function () {
    return {
    }
  },
  mounted() {
  },
  methods: {
  },
  watch: {
  },
}
</script>
