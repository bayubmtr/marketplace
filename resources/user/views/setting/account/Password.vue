<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <span class="text-besar text-tebal">Password Saya</span><br><br>
        <b-card>
          <b-row class="justify-content-md-center">
            <b-col cols="3" class="text-left">
              <p class="text-sedang form-label">Password Lama </p>
              <p class="text-sedang form-label">Password Baru </p>
              <p class="text-sedang form-label">Konfirmasi Password </p>
            </b-col>
            <b-col cols="6">
              <input type="text" placeholder="" class="form-control" name="" id="name"><br>
              <input type="text" placeholder="" class="form-control" name="" id="notelp"><br>
              <input type="text" placeholder="" class="form-control" name="" id="notelp"><br>
              <b-button variant="primary">Simpan</b-button>
            </b-col>
          </b-row>
        </b-card>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Password',
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
