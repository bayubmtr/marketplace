<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h5>Buat Toko</h5>
        <div class="dropdown-divider"></div>
        <b-row>
          <b-col cols="7">
            <form>
            <label class="text-sedang">Nama Toko</label>
            <input type="text" class="form-control" v-model="profile.store_name" required="true"><br>
            <label class="text-sedang ">Url Toko</label>
            <input @blur="checkStoreUrl" type="text" :class="'form-control '+url_check_css()" v-model="profile.store_url" required="true"><br>
            <label class="text-sedang">Slogan Toko</label>
            <input type="text" class="form-control" v-model="profile.store_slogan" required="true"><br>
            <label for="deskripsi" class="text-sedang">Deskripsi Toko</label>
            <textarea class="form-control" rows="5" id="deskripsi" v-model="profile.store_description" required="true"></textarea>
            <br>
              <b-col cols="6" class="mb-3 mb-xl-0 text-center mx-auto">
                <b-button v-on:click="createStore()" variant="primary">Buat Toko</b-button>
              </b-col>
            </form>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'create',
  data () {
    return {
      profile : {
        store_name : '',
        store_url : '',
        store_slogan : '',
        store_description : '',
      },
      url_check : null,
    }
  },
  mounted() {
  },
  methods: {
    createStore() {
      if(this.profile.store_name == '' || this.profile.store_url == '' || this.profile.store_slogan == '' || this.profile.store_description == ''){
        toastr.error('Silahkan isi semua data !');
      }else{
        axios.post('/api/user/seller/stores',  this.profile)
        .then(res => {
          this.$router.push({ name: 'store_profile'});
        })
        .catch(err => {
          console.error(err); 
        })
      }
    },
    checkStoreUrl() {
      axios.post('/api/user/seller/stores/checkstoreurl', {store_url : this.profile.store_url})
      .then(res => {
        this.url_check = res.data.is_valid;
      })
      .catch(err => {
        console.error(err); 
      })
    },
    url_check_css(){
      var x = this.url_check
      if(x == true){
        return 'is-valid'
      }else if(x == false){
        return 'is-invalid'
      }
      return ''
    }
  }
}
</script>
            