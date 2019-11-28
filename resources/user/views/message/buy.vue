<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <label class="lead text-tebal">Jasa Kirim Toko</label>
      <div role="tablist">
        <b-card no-body class="mb-1 setting-logistic">
          <b-card-header header-tag="header" class="p-1" role="tab">
          <span class="text-sedang text-tebal logistic my-auto">JNE REG </span>
          <span class="float-right"><c-switch class="mx-1 " color="primary"/><i v-b-toggle.accordion1 class="fa fa-angle-double-down fa-lg"></i></span>
          </b-card-header>
          <b-collapse id="accordion1" accordion="my-accordion" role="tabpanel">
          <b-card-body>
              <p class="card-text">
              I start opened because <code>visible</code> is <code>true</code>
              </p>
              <p class="card-text">
              {{ text }}
              </p>
          </b-card-body>
          </b-collapse>
        </b-card>
        <b-card no-body class="mb-1 setting-logistic">
          <b-card-header header-tag="header" class="p-1" role="tab">
          <span class="text-sedang text-tebal logistic my-auto">J&T REG </span>
          <span class="float-right"><c-switch class="mx-1 " color="primary"/><i v-b-toggle.accordion2 class="fa fa-angle-double-down fa-lg"></i></span>
          </b-card-header>
          <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
          <b-card-body>
              <p class="card-text">
              I start opened because <code>visible</code> is <code>true</code>
              </p>
              <p class="card-text">
              {{ text }}
              </p>
          </b-card-body>
          </b-collapse>
        </b-card>
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
    cSwitch
  },
  data () {
    return {
      slide: 0,
      sliding: null
    }
  },
  methods: {
    onSlideStart (slide) {
      console.log('onSlideStart', slide)
      this.sliding = true
    },
    onSlideEnd (slide) {
      console.log('onSlideEnd', slide)
      this.sliding = false
    }
  }
}
</script>
