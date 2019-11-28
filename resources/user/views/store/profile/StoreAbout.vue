<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <b-row>
          <b-col lg="6">
            <div>
              <b-carousel id="carousel1"
                          style="text-shadow: 1px 1px 2px #333;"
                          controls
                          indicators
                          background="#ababab"
                          :interval="4000"
                          img-width="1024"
                          img-height="480"
              >
                <!-- Slides with image only -->
                <b-carousel-slide v-for="data in slideshow" :key="data.id" :img-src="data.slideshow">
                </b-carousel-slide>
              </b-carousel>
            </div>
          </b-col>
          <b-col sm="6">
          <div class="anyClass card">
            <div class="p-3">
              <p class="lead">
              {{description}}
            </p>
            </div>
          </div>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'description',
    'slideshow'
  ],
  name: 'About'
}
</script>
