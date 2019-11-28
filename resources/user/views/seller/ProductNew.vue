<template>
  <div class="animated fadeIn">
    <b-card>
        <div slot="header">
            Tambah Barang
        </div>
        <div>
            <b-form-group label-cols="4" label-cols-lg="2" label="Foto Barang" label-for="input-sm">
              <span v-for="(item, index) in  productInfo.productPhotos.length" :key="index">
                <img id="blah" class="pr-2" :src="productInfo.productPhotos[index]" >
              </span>
                <span v-if="productInfo.productPhotos.length < 6">
                  <img id="blah" src="http://placehold.it/120x120"  onclick="document.getElementById('pic').click()">
	                <input id="pic" class='pis' v-on:change="onImageChange"  type="file" >
                </span>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Kategori Barang" label-for="input-sm">
                <b-form-select
                  id="input-3"
                  :options="main_categories"
                  required
                  text-field="name"
                  value-field="id"
                  style="width : 200px"
                  v-on:change="getSubCategories1($event)"
                ></b-form-select>
                <b-form-select v-if="sub_categories"
                  id="input-3"
                  :options="sub_categories[0]"
                  required
                  text-field="name"
                  value-field="id"
                  style="width : 200px"
                  v-on:change="getSubCategories2($event)"
                ></b-form-select>
                <b-form-select v-if="sub_categories"
                  id="input-3"
                  v-model="productInfo.category_id"
                  :options="sub_categories[1]"
                  required
                  text-field="name"
                  value-field="id"
                  style="width : 200px"
                ></b-form-select>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Nama" label-for="input-sm">
                <b-form-input v-model="productInfo.name" id="product_name" placeholder="masukan nama barang"></b-form-input>
            </b-form-group>

            <b-form-group label-cols="4" label-cols-lg="2" label="Harga Satuan" label-for="input-default">
                <b-form-input v-model="productInfo.price" placeholder="kelipatan 100" id="product_price" style="width : 200px"></b-form-input>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Berat" label-for="input-default">
                <b-form-input v-model="productInfo.weight" id="product_weight" placeholder="dalam kilo gram" style="width : 200px"></b-form-input>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Stok" label-for="input-default">
                <b-form-input v-model="productInfo.stock" id="product_stock" placeholder="Jumlah stok barang" style="width : 200px"></b-form-input>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Kondisi" label-for="input-lg">
              <b-form-radio-group id="product_condition" v-model="productInfo.condition" name="radio-sub-component">
                <b-form-radio value="New">Baru</b-form-radio>
                <b-form-radio value="Used">Bekas</b-form-radio>
              </b-form-radio-group>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Deskripsi" label-for="input-default">
                <b-form-textarea
                  id="textarea"
                  v-model="productInfo.description"
                  placeholder="Tulis keterangan barang ..."
                  rows="4"
                  max-rows="6"
                ></b-form-textarea>
            </b-form-group>
            <b-form-group label-cols="4" label-cols-lg="2" label="Spesifikasi" label-for="input-default">
                <b-row v-for="(item, index) in specificationCount" :key="index" class="mb-3">
                  <b-col cols="4">
                    <b-form-input v-model="productInfo.specification[index].name" placeholder="nama spesifikasi"></b-form-input>
                  </b-col>
                  <b-col cols="7">
                    <b-form-input v-model="productInfo.specification[index].value" placeholder="nilai spesifikasi"></b-form-input>
                  </b-col>
                </b-row>
                <b-button v-if="specificationCount < 5" v-on:click="setSpecificationCount()" variant="outline-primary">Tambah Spesifikasi</b-button>
                <b-button v-if="specificationCount > 0" v-on:click="removeSpecification()" variant="outline-danger">Hapus Spesifikasi</b-button>
            </b-form-group>
            <div class="text-center">
                <b-button variant="primary" v-on:click="storeProduct()">Simpan Barang</b-button>
            </div>
        </div>
    </b-card>
  </div>
</template>

<script>

export default {
  name: 'ProductNew',
  components: {
  },
  data: function () {
    return {
      
      main_categories : {},
      sub_categories :[],
      productInfo : {
        productPhotos : [],
        category_id : '',
        name : '',
        price : '',
        condition : '',
        description : '',
        weight : '',
        stock : '',
        specification : [
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          }
        ]
      },
      specificationCount : 0
    }
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    storeProduct(){
      axios.post('/api/user/seller/products',this.productInfo)
      .then(res => {
        this.$router.push('/seller/product');
        toastr.success('Berhasil Menambah Produk !')
      })
      .catch(err => {
        toastr.error('Gagal Menambah Produk !')
      })
    },
    getCategories(){
      axios.get('/api/user/categories')
      .then(res => {
        this.main_categories = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
    getSubCategories1(id){
      axios.get('/api/user/categories/'+id)
      .then(res => {
        Vue.set(this.sub_categories, 0, res.data)
      })
      .catch(err => {
        console.error(err); 
      })
    },
    getSubCategories2(id){
      axios.get('/api/user/categories/'+id)
      .then(res => {
        Vue.set(this.sub_categories, 1, res.data)
      })
      .catch(err => {
        console.error(err); 
      })
    },
    onImageChange(e){
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
          return;
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
          this.productInfo.productPhotos.push(e.target.result);
      };
      reader.readAsDataURL(files[0]);
    },
    removeSpecification(){
      this.specificationCount = this.specificationCount - 1;
      this.productInfo.specification[this.specificationCount].name = null;
      this.productInfo.specification[this.specificationCount].value = null;
    },
    setSpecificationCount(){
      if(this.specificationCount < 5){
        this.specificationCount = this.specificationCount + 1
      }
    }
  }
}
</script>