<template>
    <div class="wrapper">
        <div class="animated fadeIn p-2">
            <b-card v-if="star">
                <b-row>
                    <b-col cols="5" class="text-right">
                        <span class="pr-3" style="font-size:40px">{{review_avg}}</span>
                        <div>
                            <star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="4" v-bind:star-size="20"></star-rating>
                        </div>
                    </b-col>
                    <b-col cols="7">
                        <b-row>
                            <b-col cols="1" class="p-0">
                                <span>5</span><star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="1" :max-rating="1" v-bind:star-size="20"></star-rating>
                            </b-col>
                            <b-col cols="5" class="pl-0 pr-0">
                                <div class="progress align-middle mt-1">
                                    <div class="progress-bar" role="progressbar" :style="'width: '+star_five+'%'" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </b-col>
                            <b-col cols="2" class="text-left">
                                <span>{{star.five_star}}</span>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="1" class="p-0">
                                <span>4</span><star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="1" :max-rating="1" v-bind:star-size="20"></star-rating>
                            </b-col>
                            <b-col cols="5" class="pl-0 pr-0">
                                <div class="progress align-middle mt-1">
                                    <div class="progress-bar" role="progressbar" :style="'width: '+star_four+'%'" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </b-col>
                            <b-col cols="2" class="text-left">
                                <span>{{star.four_star}}</span>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="1" class="p-0">
                                <span>3</span><star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="1" :max-rating="1" v-bind:star-size="20"></star-rating>
                            </b-col>
                            <b-col cols="5" class="pl-0 pr-0">
                                <div class="progress align-middle mt-1">
                                    <div class="progress-bar" role="progressbar" :style="'width: '+star_three+'%'" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </b-col>
                            <b-col cols="2" class="text-left">
                                <span>{{star.three_star}}</span>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="1" class="p-0">
                                <span>2</span><star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="1" :max-rating="1" v-bind:star-size="20"></star-rating>
                            </b-col>
                            <b-col cols="5" class="pl-0 pr-0">
                                <div class="progress align-middle mt-1">
                                    <div class="progress-bar" role="progressbar" :style="'width: '+star_two+'%'" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </b-col>
                            <b-col cols="2" class="text-left">
                                <span>{{star.two_star}}</span>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="1" class="p-0">
                                <span>1</span><star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="1" :max-rating="1" v-bind:star-size="20"></star-rating>
                            </b-col>
                            <b-col cols="5" class="pl-0 pr-0">
                                <div class="progress align-middle mt-1">
                                    <div class="progress-bar" role="progressbar" :style="'width: '+star_one+'%'" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </b-col>
                            <b-col cols="2" class="text-left">
                                <span>{{star.one_star}}</span>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </b-card>
            <div v-for="data in review" :key="data.id">
                <b-card no-body v-if="data.review_reply == null">
                    <div slot="header">
                    <star-rating class="star-rating my-auto" v-bind:show-rating="false" v-bind:inline="true" v-bind:read-only="true" :rating="data.rating" v-bind:star-size="20"></star-rating>
                    <div>
                        <span> Oleh </span><span class="text-tebal">{{data.user.first_name}} {{data.user.last_name}} </span><span class="text-muted"> {{data.created_at}}</span>
                    </div>
                    <div>
                        <p>{{data.review}}</p>
                    </div>
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: [
    'review',
    'star',
    'store',
  ],
  data() {
      return {
      }
  },
  computed: {
      star_one: function(){
           if(this.star){
               return this.star.one_star / this.star.review_count * 100
           }
      },
      star_two: function(){
          if(this.star){
           return this.star.two_star / this.star.review_count * 100
          }
      },
      star_three: function(){
          if(this.star){
           return this.star.three_star / this.star.review_count * 100
          }
      },
      star_four: function(){
          if(this.star){
           return this.star.four_star/ this.star.review_count * 100
          }
      },
      star_five: function(){
          if(this.star){
           return this.star.five_star / this.star.review_count * 100
          }
      },
      review_avg: function(){
          if(this.star){
              return this.star.review_avg
          }
          return 0;
      }
  },
  mounted() {
    if(this.star){
        this.total_star = this.star.review_count;
    }
  },
}
</script>