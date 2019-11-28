<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h5>Profile Toko</h5>
        <div class="dropdown-divider"></div>
        <b-row>
          <b-col lg="5">
            <div class='card card-profile'>
              <img alt='baner toko (1165x185)' class='card-img-top mouse-pointer' :src="Store.background" onclick="document.getElementById('store_background').click()">
              <div class='card-block'>
                <img alt='' class='card-img-profile mouse-pointer' :src="Store.logo" onclick="document.getElementById('store_logo').click()" style="width : 170px; height : 170px">
                <h5 class='card-title'>
                  {{Store.name}}
                  <small>Bergabung {{Store.created_at | moment("Do MMMM YYYY")}}</small>
                  <input v-on:change="onBackgroundChange" id="store_background" type="file" style="display:none" />
                  <input v-on:change="onLogoChange" id="store_logo" type="file" style="display:none"/>
                </h5>
              </div>
            </div>
            <p class="text-sedang">Kunjungi Toko <router-link :to="'/'+Store.store_url"><span class="float-right fa fa-desktop"></span></router-link></p>
            <p class="text-sedang">Product<span class="float-right text-red" v-if="Store">{{Store.product_count}}</span></p>
            <p class="text-sedang">Pengikut<span class="float-right text-red">{{Store.follower_count}}</span></p>
            <p class="text-sedang">Penilaian<span class="float-right text-red">{{Store.review_avg}} ({{Store.review_count}})</span></p>
          </b-col>
          <b-col cols="7">
            <label class="text-sedang"><i class="fa fa-building"></i> Nama Toko</label>
            <input type="text" class="form-control" v-model="profile.store_name"><br>
            <label class="text-sedang"><i class="fa fa-bullhorn"></i> Slogan Toko</label>
            <input type="text" class="form-control" v-model="profile.slogan"><br>
            <label class="text-sedang"><i class="fa fa-picture-o"></i> Deskripsi Gambar (550 x 185)</label>
            <b-row>
              <template v-for="(item, index) in Store.slideshow" >
              <div class="col-sm-3 text-center" :key="index">
                  <img :src="item.slideshow" alt="" style="height: 70px; width: 111px">
                  <span v-on:click="deleteSlideshow(item.id, item.slideshow)" class="text-danger mouse-pointer">Hapus</span>
              </div>
              <div v-if="index+1 == Store.slideshow.length && index < 3" class="col-sm-3 text-center mouse-pointer" :key="index" onclick="document.getElementById('store_slideshow').click()">
                  <img :src="'/storage/icon/plus.png'" style="height: 70px; width: 111px" alt="">
              </div>
              </template>
              <div v-if="Store.slideshow == ''" class="col-sm-3 text-center mouse-pointer" onclick="document.getElementById('store_slideshow').click()">
                  <img :src="'/storage/icon/plus.png'" style="height: 70px; width: 111px" alt="">
              </div>
              <input v-on:change="onImageChange" id="store_slideshow" type="file" style="display:none"/>
            </b-row><br>
            <label for="deskripsi" class="text-sedang"><i class="fa fa-pencil"></i> Deskripsi Toko</label>
            <textarea class="form-control" rows="5" id="deskripsi" v-model="profile.store_description"></textarea>
            <br>
              <b-col cols="6" class="mb-3 mb-xl-0 text-center mx-auto">
                <b-button v-on:click="updateProfiles()" variant="primary">Simpan</b-button>
              </b-col>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Profile',
  data () {
    return {
      Store : {},
      image : '',
      background : '',
      logo : '',
      profile : {
        store_name : '',
        slogan : '',
        store_description : '',
        type : 'update_profile'
      }
    }
  },
  mounted() {
    this.getStore();
  },
  methods: {
    getStore() {
      axios.get('/api/user/seller/stores')
      .then(res => {
        this.Store = res.data
        this.profile.store_name = res.data.name
        this.profile.slogan = res.data.slogan
        this.profile.store_description = res.data.description
      })
      .catch(err => {
        this.profile.type = 'new_store'
      })
    },
    updateProfiles() {
      axios.patch('/api/user/seller/stores/'+this.Store.id,  this.profile)
      .then(res => {
        this.getStore();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    storeSlideshow() {
      axios.patch('/api/user/seller/stores/'+this.Store.id, {type : 'add_slideshow', image : this.image})
      .then(res => {
        this.getStore();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    updateLogo() {
      axios.patch('/api/user/seller/stores/'+this.Store.id, {type : 'update_logo', logo : this.logo})
      .then(res => {
        this.getStore();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    updateBackground() {
      axios.patch('/api/user/seller/stores/'+this.Store.id, {type : 'update_background', background : this.background})
      .then(res => {
        this.getStore();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    deleteSlideshow(id, name) {
      axios.patch('/api/user/seller/stores/'+this.Store.id, {type : 'delete_slideshow', 'slideshow_id' : id, 'file_name' : name})
      .then(res => {
        this.getStore();
      })
      .catch(err => {
        console.error(err); 
      })
    },
    onImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
          return;
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
          vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    onLogoChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
          return;
      this.createLogoImage(files[0]);
    },
    onBackgroundChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
          return;
      this.createBackgroundImage(files[0]);
    },
    createLogoImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
          vm.logo = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    createBackgroundImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
          vm.background = e.target.result;
      };
      reader.readAsDataURL(file);
    },
  },
  watch: {
    image(){
      this.storeSlideshow();
    },
    logo(){
      this.updateLogo();
    },
    background(){
      this.updateBackground();
    }
  },
}
</script>
            