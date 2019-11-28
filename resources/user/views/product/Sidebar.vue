<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card>
        <h6 class="lead">KATEGORI</h6>
        <div class="list-group mt-1 pl-0" v-for="(item, index) in categories" :key="index">
          <div class="form-check form-check-inline">
            <input class="form-check-input" v-on:change="setCategory(item.id)" name="a" type="radio" id="inlineCheckbox1" value="option1">
            <strong v-b-toggle="'a'+index">{{item.name}}</strong>
          </div>
          <b-collapse :id="'a'+index" class="ml-2" v-for="(item2, index2) in item.childs" :key="index2">
            <template v-if="item2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" v-on:change="setCategory(item2.id)" name="a" type="radio" id="inlineCheckbox1" value="option1">
                <strong v-b-toggle="'collapse-'+index2+'-inner'">{{item2.name}}</strong>
              </div>
              <b-collapse :id="'collapse-'+index2+'-inner'" class="ml-2" v-for="(item3, index3) in item2.childs" :key="index3">
                <div class="form-check form-check-inline">
                <input class="form-check-input" v-on:change="setCategory(item3.id)" name="a" type="radio" id="inlineCheckbox1" value="option1">
                <strong>{{item3.name}}</strong>
              </div>
              </b-collapse>
            </template>
          </b-collapse>
        </div>
        <div class="dropdown-divider"></div>
        <br>
        <h6  class="lead">PENILAIAN</h6>
          <p class="penilaian" v-on:click="setRate('5')">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
          </p>
          <p class="penilaian" v-on:click="setRate('4')">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star-o"></span>
          </p>
          <p class="penilaian" v-on:click="setRate('3')">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
          </p>
          <p class="penilaian" v-on:click="setRate('2')">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
          </p>
          <p class="penilaian" v-on:click="setRate('1')">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
            <span class="fa fa-star-o"></span>
          </p>
          <div class="dropdown-divider"></div>
          <br>
        <div>
          <h6 class="lead">LOKASI</h6>
          <model-list-select :list="addressesResult"
                             option-value="city_id"
                             option-text="text"
                             v-model="selectedAddress"
                             placeholder="Ketik Lokasi"
                             @searchchange="searchAddress">
          </model-list-select>
        <div class="dropdown-divider"></div>
        </div>
        <div class="mt-3">
          <h6  class="lead">BATAS HARGA</h6>
          <input type="text" @blur="minPrice()" v-model="price.minPrice" placeholder="Harga Min" class="form-control" style="max-width:135px">
          <p class="text-center" style="margin-bottom:0px">Sampai</p>
          <input type="text" @blur="maxPrice()" v-model="price.maxPrice" placeholder="Harga Max" class="form-control" style="max-width:135px">
        </div>
          <div class="mt-3">
            <b-button v-on:click="resetFilter()" block variant="primary">Hapus Filter</b-button>
          </div>
      </b-card>
    </div>
  </div>
</template>

<script>
import { ModelListSelect } from 'vue-search-select'
export default {
  props: [
    'category',
    'store'
  ],
  components : {
      ModelListSelect,
  },
  name: 'Promo',
  data () {
    return {
      slide: 0,
      sliding: null,
      categories : {},
      price : {
        minPrice : '',
        maxPrice : ''
      },
      addressesResult : [],
      selectedAddress : '',
      search : this.$route.query.search,
      text: ' Anim pariatur cliche reprehenderit'
    }
  },
  mounted() {
    this.getCategory();
  },
  methods: {
    getCategory(category){
      axios.get('/api/user/categories?type=all')
      .then(res => {
        this.categories = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
    searchAddress(searchText) {
        if(searchText.length > 2 && searchText.length < 6){
          axios.get('/api/user/addresses?search='+searchText+'&type=city').then(response => {
          this.addressesResult = response.data
          })
        }
    },
    setCategory(id){
      this.$router.push({ query: Object.assign({}, this.$route.query, { cat: id }) })
    },
    setRate(rate){
      this.$router.push({ query: Object.assign({}, this.$route.query, { rate: rate }) })
    },
    minPrice(value){
      this.$router.push({ query: Object.assign({}, this.$route.query, { minPrice: this.price.minPrice }) })
    },
    maxPrice(value){
      this.$router.push({ query: Object.assign({}, this.$route.query, { maxPrice: this.price.maxPrice }) })
    },
    resetFilter(){
      this.$router.push('/products?search='+this.search);
    }
  },
  watch: {
    selectedAddress(){
      this.$router.push({ query: Object.assign({}, this.$route.query, { location: this.selectedAddress }) })
    }
  },
}
</script>